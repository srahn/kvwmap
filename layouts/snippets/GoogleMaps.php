<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/GoogleMaps_'.$this->user->rolle->language.'.php');
 ?>
<script language="JavaScript">
<!--


//-->
</script>


<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td align="center" colspan="3"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <tr> 
    <td rowspan="3">&nbsp;</td>
    <td colspan="2" rowspan="3"> 
      <iframe src="<? echo $this->googlelink; ?>" width="805" height="840" name="google">
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>     
    	<INPUT TYPE="HIDDEN" NAME="go" VALUE="googlemaps" >
    	<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
    	