<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <th><a href="index.php?go=Layer_Anzeigen&order=Layer_ID"><?php echo $this->strID; ?></a></th>
        <th><a href="index.php?go=Layer_Anzeigen&order=Name"><?php echo $this->strName; ?></a></th>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php 
      for ($i=0;$i<count($this->layerdaten['ID']);$i++) { ?>
      <tr onMouseover="this.bgColor='<?php echo BG_TR; ?>'" onMouseout="this.bgColor=''">
        <td><?php echo $this->layerdaten['ID'][$i]; ?></td>
        <td><?php echo $this->layerdaten['Bezeichnung'][$i]; ?></td>
        <td>&nbsp;<a href="index.php?go=Layereditor&selected_layer_id=<? echo $this->layerdaten['ID'][$i]; ?>"><?php echo $this->strChange; ?></a></td>
        <td>&nbsp;&nbsp;<a href="javascript:Bestaetigung('index.php?go=Layer_Löschen&selected_layer_id=<? echo $this->layerdaten['ID'][$i]; ?>&order=<? echo $this->formvars['order']; ?>','Wollen Sie Layer <?php echo $this->layerdaten['Bezeichnung'][$i]; ?> wirklich löschen?')"><?php echo $this->strDelete; ?></a></td>        
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
      <input type="hidden" name="order" value="<?php echo $this->formvars['order']; ?>">
