<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="2"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td> 
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left"><b>noch experimentel</b><br>
    Von den Layern in dieser Stelle wurde ein Web Map Context Document (WMC) erzeugt.<br>
    Derzeit werden nur die Layer ausgegeben, die WMS als Quellen haben und vom Nutzer in dieser Stelle ausgewählt wurden.<br>
    Zu diesen Layern fehlen jedoch auch noch Metadaten, die für die Layer erfasst werden müssen, bzw. beim LoadMap in das MapObject gelesen werden müssen.<br>
    WMC-Datei können Sie unter folgendem Link ansehen oder herunterladen:<br>
    <a href="<?php echo TEMPPATH_REL.$this->WMCFileName; ?>" target="_blank"><?php echo TEMPPATH_REL.$this->WMCFileName; ?></a>    
    </td>
  </tr>
</table>
