<h2>HIDA Dokumente eingelesen</h2>
<?php if ($this->Fehlermeldung!='') {
  include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?>  
<table border="1">
  <tr>
	  <td>docKey</td>
	  <td>blockKey</td>
	  <td>parentField</td>
	  <td>fieldKey</td>
	  <td>fieldValue</td>
  </tr>
  <?php
  for ($i=0;$i<count($this->fields);$i++) { 
	  ?>
	  <tr>
			<td>&nbsp;<?php echo $this->fields[$i]['docKey']; ?></td>
			<td>&nbsp;<?php echo $this->fields[$i]['blockKey']; ?></td>
			<td>&nbsp;<?php echo $this->fields[$i]['parentField']; ?></td>
			<td>&nbsp;<?php echo $this->fields[$i]['fieldKey']; ?></td>
			<td>&nbsp;<?php echo $this->fields[$i]['fieldValue']; ?></td>
		</tr>
		<?php
	}
	?>
</table>