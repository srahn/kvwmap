<h2><?php echo $this->qlayerset[$i]['Name']; ?></h2>
<?php
  $layer_id=$this->qlayerset[$i]['Layer_ID'];
  $this->nachweis = new Nachweis($this->pgdatabase, $this->user->rolle->epsg_code);
	$hauptdokumentarten = $this->nachweis->getHauptDokumentarten();
?>

<table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>">
    <td><span class="fett">Nummer</span></td>
    <td>Blatt-Nr.</td>
    <td>Art</td>
    <td>Flur</td>
    <td>Format</td>
    <td><span class="fett">Vermessungsstelle</span></td>
    <td><span class="fett">Datum</span></td>
    <td><span class="fett">Riss</span></td>

  </tr>
  <?php
  for ($j=0;$j<count($this->qlayerset[$i]['shape']);$j++) {
    $oid=$this->qlayerset[$i]['shape'][$j]['oid'];
    $id=$this->qlayerset[$i]['shape'][$j]['id'];
    $flur=$this->qlayerset[$i]['shape'][$j]['flur'];
    $gemarkid=$this->qlayerset[$i]['shape'][$j]['gemarkung'];
		$flurid=$this->qlayerset[$i]['shape'][$j]['flurid'];
    $art=$this->qlayerset[$i]['shape'][$j]['art'];
	
    while (strlen($stammnr) < 8)
    {
      $stammnr="0".$stammnr;
    }
$dname=$this->qlayerset[$i]['shape'][$j]['link_datei'];
    ?>
  <tr>
	<td><span class="fett"><?php echo $this->qlayerset[$i]['shape'][$j][NACHWEIS_PRIMARY_ATTRIBUTE]; ?></span></td>
	<td><?php echo $this->qlayerset[$i]['shape'][$j]['blattnummer']; ?></td>
        <td><?php
           echo $hauptdokumentarten[$art]['abkuerzung'];
        ?></td>
	<td><?php echo $flurid; ?></td>
        <td><?php echo $this->qlayerset[$i]['shape'][$j]['format']; ?></td>

<td><?php echo $this->qlayerset[$i]['shape'][$j]['name']; ?></td>
    <td><?php echo $this->qlayerset[$i]['shape'][$j]['datum']; ?></td>
    <td><a href="<?php echo copy_file_to_tmp($dname); ?>" Target="about_blank">anzeigen</a></td>
  </tr>
  <?php
  }
  ?>
</table><br />
