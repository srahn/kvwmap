<h2>Gemeinden</h2>

<table border="1" cellspacing="0" cellpadding="2">

  <tr bgcolor="<?php echo BG_DEFAULT ?>"> 

    <td><span class="fett">Gemeinde</span></td>

    <td><span class="fett">Gemeindeschl&uuml;ssel</span></td>

    <td><span class="fett">Amt</span></td>

    <td><span class="fett">Link</span></td>

  </tr>

  <?php

  for ($j=0;$j<count($this->qlayerset[$i]['shape']);$j++) {

    ?> 

  <tr> 

    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['GEMEINDE']; ?></td>

    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['GEMEINDE_L']; ?></td>

    <td><?php echo $this->qlayerset[$i]['shape'][$j]->values['AMT_LANG_I']; ?></td>

    <td><a href="<?php echo $this->qlayerset[$i]['shape'][$j]->values['LINK']; ?>" target="_blank">

    <?php echo $this->qlayerset[$i]['shape'][$j]->values['LINK']; ?></a></td>

  </tr>

  <?php

  }

  ?> 

</table>