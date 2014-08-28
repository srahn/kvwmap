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
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr>
    <td>
    	<table width="100%" border="0" style="border:2px solid <?php echo BG_DEFAULT ?>"cellspacing="0" cellpadding="3">
	      <tr>
	        <th style="border-right:1px solid <?php echo BG_DEFAULT ?>">Layer</th>
	        <th style="border-right:1px solid <?php echo BG_DEFAULT ?>">Kurzbeschreibung</th>
	        <th>Datenherr</th>
	      </tr>
	      <?php 
	      for($i = 0; $i < count($this->layer['ID']); $i++){
	      	if($lastgroup != $this->layer['Gruppe'][$i]){  	
	      		$lastgroup = $this->layer['Gruppe'][$i]; ?>
	      	<tr>
	      		<td colspan="3" bgcolor="<?php echo BG_DEFAULT ?>" style="border:1px solid <?php echo BG_DEFAULT ?>"><span class="fett"><? echo $this->layer['Gruppe'][$i]; ?></span></td>
	      	</tr>
		   <? } ?>
		      <tr>
		        <td style="border-right:1px solid <?php echo BG_DEFAULT ?>; border-bottom:1px solid <?php echo BG_DEFAULT ?>"><? echo $this->layer['Bezeichnung'][$i]; ?></td>
		        <td style="border-right:1px solid <?php echo BG_DEFAULT ?>; border-bottom:1px solid <?php echo BG_DEFAULT ?>"><? echo $this->layer['Kurzbeschreibung'][$i]; ?>&nbsp;</td>
		        <td style="border-bottom:1px solid <?php echo BG_DEFAULT ?>"><? echo $this->layer['Datenherr'][$i]; ?>&nbsp;</td>
		      </tr>
		   <? } ?>
	    </table>
    </td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>
