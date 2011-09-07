<h2><?php echo $this->qlayerset[$i]['Name']; ?></h2>
<table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>">
    <td><b>Bundesland</b></td>

  </tr>
  <?php
  for ($j=0;$j<count($this->qlayerset[$i]['shape']);$j++) {
    ?>
  <tr>
    <td><?php echo $this->qlayerset[$i]['shape'][$j]['name']; ?></td>
  </tr>
  <?php
  }
  ?>
</table><br />