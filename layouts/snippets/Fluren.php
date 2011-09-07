<h2><?php echo $this->qlayerset[$i]['Name']; ?></h2>
<table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>"> 
    <td><b>Flur</b></td>
    <td><b>Gemarkungsschlüssel</b></td>
    <td><b>Folie</b></td>
    <td><b>Objnr</b></td>
    <td><b>Objart</b></td>  
  </tr>
  <?php	for($k = 0; $k < count($this->qlayerset[$i]['shape']); $k++){		$multiarray['flur'][] = $this->qlayerset[$i]['shape'][$k]['flur'];		$multiarray['gemkgschl'][] = $this->qlayerset[$i]['shape'][$k]['gemkgschl'];		$multiarray['folie'][] = $this->qlayerset[$i]['shape'][$k]['folie'];		$multiarray['oid'][] = $this->qlayerset[$i]['shape'][$k]['oid'];		$multiarray['objart'][] = $this->qlayerset[$i]['shape'][$k]['objart'];	}	@array_multisort($multiarray['flur'], $multiarray['gemkgschl'], $multiarray['folie'], $multiarray['oid'], $multiarray['objart']);
  for ($j=0;$j<count($this->qlayerset[$i]['shape']);$j++) {
    ?> 	<tr>     <td><?php echo $multiarray['flur'][$j]; ?></td>    <td><?php echo $multiarray['gemkgschl'][$j]; ?></td>    <td><?php echo $multiarray['folie'][$j]; ?></td>    <td><?php echo $multiarray['oid'][$j]; ?></td>    <td><?php echo $multiarray['objart'][$j]; ?></td>  </tr>
  <?php
  }
  ?> 
</table><br />