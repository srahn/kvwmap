<?php
  # 2007-12-30 pk
  include(LAYOUTPATH.'languages/layer2stelle_order_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
?><script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><strong><font size="+1"><?php echo $strTitle.' '.$this->selected_stelle->Bezeichnung; ?></font></strong></td>
  </tr>
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
    	<tr>
    		<td rowspan="2" style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-top:1px solid #C3C7C3" class="bold">&nbsp;<a href="index.php?go=Layer2Stelle_Reihenfolge&selected_stelle_id=<? echo $this->formvars['selected_stelle_id']; ?>&order=Name"><?php echo $this->strLayer; ?></a></td>
    		<td rowspan="2" style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-top:1px solid #C3C7C3" class="bold">&nbsp;<a href="index.php?go=Layer2Stelle_Reihenfolge&selected_stelle_id=<? echo $this->formvars['selected_stelle_id']; ?>&order=drawingorder"><?php echo $strDrawingOrder; ?></a></td>
    		<td colspan="2" style="border-top:1px solid #C3C7C3; border-right:1px solid #C3C7C3; border-left:1px solid #C3C7C3" align="center" class="bold"><?php echo $strProperties; ?></td>
    	</tr>
    	<tr>
    		<td style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3" class="bold"><?php echo $strTask; ?></td>
    		<td style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-right:1px solid #C3C7C3" class="bold"><?php echo $strGlobal; ?></td>
    	</tr>
      <?
      for($i = 0; $i < count($this->layers['ID']); $i++){
      	echo '
      	<tr>
      		<td style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3">&nbsp;'.$this->layers['Bezeichnung'][$i].'</td>
      		<td style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3" align="center"><input size="7" type="text" name="drawingorder_layer'.$this->layers['ID'][$i].'" value="'.$this->layers['drawingorder'][$i].'"</td>
      		<td style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3" align="center"><a href="index.php?go=Layer2Stelle_Editor&selected_layer_id='.$this->layers['ID'][$i].'&selected_stelle_id='.$this->formvars['selected_stelle_id'].'&stellen_name='.$this->selected_stelle->Bezeichnung.'">'.$this->strChange.'</a></td>
      		<td style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" align="center"><a href="index.php?go=Layereditor&selected_layer_id='.$this->layers['ID'][$i].'">'.$this->strChange.'</a></td>
      	</tr>
      	';
      }
      ?> 
    </table>
    </td>
  </tr>
  <tr>
  	<td align="center">
  		<input type="hidden" id="go_plus" name="go_plus" value="">
  		<input type="button" name="zurueck" value="<?php echo $this->strButtonBack; ?>" onclick="document.location.href='index.php?go=Stelleneditor&selected_stelle_id=<? echo $this->formvars['selected_stelle_id'];?>'">
      <input type="button" name="dummy" value="<?php echo $this->strSave; ?>" onclick="submitWithValue('GUI','go_plus','Speichern')">
  	</td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="go" value="Layer2Stelle_Reihenfolge">
<input type="hidden" name="selected_stelle_id" value="<? echo $this->formvars['selected_stelle_id'];?>">
