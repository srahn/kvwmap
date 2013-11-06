<script type="text/javascript">
<!--

	function metadata_create(layer_id){
		document.getElementById('meta').style.display='block';
	  document.getElementById('meta').src='<? echo METADATA_AUTH_LINK; ?>';
	  // ajax Request an kvwmap -> liefert XML -> das wird per POST an geonetwork CSW geschickt
	  document.getElementById('meta').src='<? echo METADATA_EDIT_LINK; ?>';
	}
	
//-->
</script>

<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr>
  	<td><iframe width="1020" height="700" id="meta" src="<? echo METADATA_SEARCH_LINK; ?>"></iframe></td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>
      <input type="hidden" name="order" value="<?php echo $this->formvars['order']; ?>">
