<?php
 $ret=$this->database->getStyles($Class_ID,trim($this->formvars['order'].' '.$this->formvars['orderdirection']));
 if ($ret[0]) {
   # Fehler bei der Abfrage
 }
 else {
   $this->styledaten=$ret[1];
 }
 if ($this->formvars['orderdirection']=='ASC') {
   $orderdirection='DESC';
 }
 else {
   $orderdirection='ASC';
 }
 
# var_dump($this->styledaten);
?>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <th>&nbsp;</th>
		<th><a href="index.php?go=test&order=Style_ID&orderdirection=<?php echo $orderdirection; ?>">Style_ID</a></th>
        <th><a href="index.php?go=test&order=symbolname&orderdirection=<?php echo $orderdirection; ?>">symbolname</a></th>
        <th><a href="index.php?go=test&order=symbol&orderdirection=<?php echo $orderdirection; ?>">symbol</a></th>
		<th><a href="index.php?go=test&order=color&orderdirection=<?php echo $orderdirection; ?>">color</a></th>
        <th><a href="index.php?go=test&order=outlinecolor&orderdirection=<?php echo $orderdirection; ?>">outlinecolor</a></th>
        <th><a href="index.php?go=test&order=size&orderdirection=<?php echo $orderdirection; ?>">size</a></th>
	  </tr>
      <?php 
      for ($i=0;$i<count($this->styledaten);$i++) { ?>
		  <tr>
			<td><?php echo drawColorBox($this->styledaten[$i]['color'],$this->styledaten[$i]['outlinecolor']); ?></td>
			<td><?php echo $this->styledaten[$i]['Style_ID']; ?></td>
			<td><?php echo $this->styledaten[$i]['symbolname']; ?></td>
			<td><?php echo $this->styledaten[$i]['symbol']; ?></td>			
			<td><?php echo $this->styledaten[$i]['color']; ?></td>
			<td><?php echo $this->styledaten[$i]['outlinecolor']; ?></td>
			<td><?php echo $this->styledaten[$i]['size']; ?></td>			
		  </tr>
        <?php
      }
      ?>
    </table></td>
  </tr>
</table>
<a href="index.php?go=Administratorfunktionen">zurück</a>
