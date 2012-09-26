<h2><?php echo $this->qlayerset[$i]['Name']; ?></h2>
<?php
 #echo '<br>'.$this->qlayerset[$i][GetFeatureInfoRequest];
?>


  <iframe style="border:none;"  src="<?php echo $this->qlayerset[$i][GetFeatureInfoRequest]; ?>" width="90%" height="500">
    Wenn Sie dies hier lesen können, unterstützt Ihr Browser keine iframes.
  </iframe>



<?php
/*
  $response=file_get_contents($this->qlayerset[$i][GetFeatureInfoRequest]);
  if ($response=='') {
    ?><br>An dieser Position konnten zu diesem Layer keine Objekte gefunden werden.<br><?php
  }
  else {
    echo $response;
  }
*/
?>