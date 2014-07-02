
<script type="text/javascript">
<!--

//-->
</script>


<input type="hidden" name="go" value="">

<h2><?php echo $this->titel; ?></h2>
      

<table border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>
			<table width="400" cellpadding="4" cellspacing="2" style="border:1px solid #C3C7C3;border-collapse:collapse">
		<?	$layer_id = ''; 
				for($i = 0; $i < count($this->searches); $i++){
					if($layer_id != $this->searches[$i]['layer_id']){	?>
						<tr>
							<td colspan="3" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;border:1px solid #C3C7C3"><span class="fett"><? echo $this->searches[$i]['layername']; ?></span></td>
						</tr>
		<?		}
					$layer_id = $this->searches[$i]['layer_id'];
			?>
					<tr>
						<td><? echo $this->searches[$i]['name']; ?></td>
						<td><a href="index.php?go=Layer-Suche&selected_layer_id=<? echo $layer_id; ?>&searches=<? echo $this->searches[$i]['name']; ?>">Laden</a></td>
					</tr>
				<? } ?>
      </table> 
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr> 
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="go_plus" value="">
