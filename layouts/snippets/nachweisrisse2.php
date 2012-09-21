<h2><?php echo $this->qlayerset[$i]['Name']; ?></h2>
<?php
  $layer_id=$this->qlayerset[$i]['Layer_ID'];
?>

<table border="1" cellspacing="0" cellpadding="2">
  <tr bgcolor="<?php echo BG_DEFAULT ?>">
    <td><b>Nummer</b></td>
    <td>Blatt-Nr.</td>
    <td>Art</td>
    <td>Flur</td>
    <td>Format</td>
    <td><b>Vermessungsstelle</b></td>
    <td><b>Datum</b></td>
    <td><b>Riss</b></td>

  </tr>
  <?php
  for ($j=0;$j<count($this->qlayerset[$i]['shape']);$j++) {
    $nr=$this->qlayerset[$i]['shape'][$j][NACHWEIS_PRIMARY_ATTRIBUTE];
    $oid=$this->qlayerset[$i]['shape'][$j]['oid'];
    $id=$this->qlayerset[$i]['shape'][$j]['id'];
    $flurid=$this->qlayerset[$i]['shape'][$j]['flurid'];
    $gemarkid=substr($flurid,2,4);
    $flur=substr($flurid,6,3);
    $flurid=$gemarkid."-".$flur;
    $art=$this->qlayerset[$i]['shape'][$j]['art'];

    while (strlen($stammnr) < 8)
    {
      $stammnr="0".$stammnr;
    }
$dname=NACHWEISDOCPATH.$this->qlayerset[$i]['shape'][$j]['flurid']."/".$nr."/".$this->qlayerset[$i]['shape'][$j]['link_datei'];
    ?>
  <tr>
	<td><b><?php echo $this->qlayerset[$i]['shape'][$j]['nummer']; ?></b></td>
	<td><?php echo $this->qlayerset[$i]['shape'][$j]['blattnummer']; ?></td>
        <td><?php
           if ($art == '100') echo "FFR";
           if ($art == '010') echo "KVZ";
           if ($art == '001') echo "GN ";
           if ($art == '111') echo "ANDERE ";
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
