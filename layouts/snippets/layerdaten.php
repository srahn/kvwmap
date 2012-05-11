<a name="oben"></a>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
<? if($this->formvars['order']=="Name") { ?>
  <tr height="50px" valign="bottom">
    <td>
    <? $umlaute=array("Ä","Ö","Ü");
       for ($i=0;$i<count($this->layerdaten['ID']);$i++) {
         if(!in_array(strtoupper(substr($this->layerdaten['Bezeichnung'][$i],0,1)),$umlaute) AND strtolower(substr($this->layerdaten['Bezeichnung'][$i],0,1)) != $first) {
           echo "<a href='#".strtoupper(substr($this->layerdaten['Bezeichnung'][$i],0,1))."'>".strtoupper(substr($this->layerdaten['Bezeichnung'][$i],0,1))."</a>&nbsp;&nbsp;";
           $first=strtolower(substr($this->layerdaten['Bezeichnung'][$i],0,1));
         }
       } ?> 
    </td>
  </tr>
<? } ?>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td>&nbsp;</td>
        <th align="left"><a href="index.php?go=Layer_Anzeigen&order=Layer_ID"><?php echo $this->strID; ?></a></th>
        <th align="left"><a href="index.php?go=Layer_Anzeigen&order=Name"><?php echo $this->strName; ?></a></th>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?  
      for ($i=0;$i<count($this->layerdaten['ID']);$i++) { 
      if($this->formvars['order']=="Name") {
      if(!in_array(strtoupper(substr($this->layerdaten['Bezeichnung'][$i],0,1)),$umlaute) AND strtolower(substr($this->layerdaten['Bezeichnung'][$i],0,1)) != $first) { ?>
      <tr>
        <th align="left" style="border-top:1px solid #808080; margin:0px;">
          <? echo "<a name='".strtoupper(substr($this->layerdaten['Bezeichnung'][$i],0,1))."'>".strtoupper(substr($this->layerdaten['Bezeichnung'][$i],0,1))."</a>";
          $first=strtolower(substr($this->layerdaten['Bezeichnung'][$i],0,1)); ?>
        </td>
        <td colspan="4" align="right" style="border-top:1px solid #808080; margin:0px;">
          <a href="#oben"><img src="<? echo GRAPHICSPATH; ?>pfeil2.gif" width="11" height="11" border="0"></a>
        </td>
      </tr>
      <? }
      } ?>      
      <tr onMouseover="this.bgColor='<?php echo BG_TR; ?>'" onMouseout="this.bgColor=''">
        <td>&nbsp;</td>
        <td><?php echo $this->layerdaten['ID'][$i]; ?>&nbsp;&nbsp;</td>
        <td><?php echo $this->layerdaten['Bezeichnung'][$i]; ?></td>
        <td>&nbsp;<a href="index.php?go=Layereditor&selected_layer_id=<? echo $this->layerdaten['ID'][$i]; ?>"><?php echo $this->strChange; ?></a></td>
        <td>&nbsp;&nbsp;<a href="javascript:Bestaetigung('index.php?go=Layer_Löschen&selected_layer_id=<? echo $this->layerdaten['ID'][$i]; ?>&order=<? echo $this->formvars['order']; ?>','Wollen Sie Layer <?php echo $this->layerdaten['Bezeichnung'][$i]; ?> wirklich löschen?')"><?php echo $this->strDelete; ?></a></td>        
      </tr>
      <? } ?>
    </table></td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>
      <input type="hidden" name="order" value="<?php echo $this->formvars['order']; ?>">
