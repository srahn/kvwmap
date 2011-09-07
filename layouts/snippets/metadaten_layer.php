<script type="text/javascript">
<!--

	function metadata_create(layer_id){
		document.getElementById('meta').style.display='block';
	  document.getElementById('meta').src='<? echo METADATA_AUTH_LINK; ?>';
	  // ajax Request an kvwmap -> liefert XML -> das wird per POST an geonetwork CSW geschickt
	  document.getElementById('meta').src='<? echo METADATA_EDIT_LINK; ?>'+layer_id;
	}
	
//-->
</script>

<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr>
  	<td><iframe style="display:none" width="990" height="500" id="meta" src=""></iframe></td>
  </tr>
  <tr>
    <td>
    	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	      <tr>
	        <th align="left"><a href="index.php?go=Metadaten_Uebersicht&order=Layer_ID"><?php echo $this->strID; ?></a></th>
	        <th><a href="index.php?go=Metadaten_Uebersicht&order=Name"><?php echo $this->strName; ?></a></th>
	        <td>&nbsp;</td>
	      </tr>
	      <?php 
	      for ($i=0;$i<count($this->layerdaten['ID']);$i++) { ?>
	      <tr onMouseover="this.bgColor='<?php echo BG_TR; ?>'" onMouseout="this.bgColor=''">
	        <td><?php echo $this->layerdaten['ID'][$i]; ?></td>
	        <td><?php echo $this->layerdaten['Bezeichnung'][$i]; ?></td>
	        <td>&nbsp;<a href="javascript:metadata_create(<? echo $this->layerdaten['ID'][$i]; ?>);">Metadaten erfassen</a></td>        
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
