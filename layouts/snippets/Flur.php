<h2><?php echo $this->qlayerset[$i]['Name']; ?></h2>
<table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>"> 
    <td><b>Flur</b></td>
    <td><b>Flur&nbsp;ID</b></td>
    <td><b>Gemarkung</b></td>
    <td><b>Gemarkungs-<br>
      schl&uuml;ssel</b></td>
    <td><b>Gemeinde</b></td>
    <td><b>Amt</b></td>
    <td><b>Kreis</b></td>
  </tr>
  <?php
  for ($j=0;$j<count($this->qlayerset[$i]['shape']);$j++) {
    ?> 
  <tr> 
    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['FLUR']; ?></td>
    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['FLUR_ID']; ?></td>
    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['GEMARKUNG']; ?></td>
    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['GEMKGSCHL']; ?></td>
    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['GEMEINDE']; ?></td>
    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['AMT']; ?></td>
    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['KREIS']; ?></td>
  </tr>
  <?php
  }
  ?> 
</table><br />