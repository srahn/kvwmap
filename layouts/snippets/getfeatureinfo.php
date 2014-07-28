<br>
<h2><?php echo $this->qlayerset[$i]['Name']; ?></h2>
<br>
<?php
 #echo '<br>'.$this->qlayerset[$i][GetFeatureInfoRequest];
?>

<?

	if(substr($this->qlayerset[$i][GetFeatureInfoRequest],0,7) == substr(URL,0,7)){				# eigener Server und WMS-Server haben beide das gleiche Protokoll (beide http oder beide https)

?>

  <iframe style="min-width:500px;width:90%;height:500px;border:none;"  src="<?php echo $this->qlayerset[$i][GetFeatureInfoRequest]; ?>">
    Wenn Sie dies hier lesen können, unterstützt Ihr Browser keine iframes.
  </iframe><br><br>
	
<? }else{																																								# sie haben unterschiedliche Protokolle und daher kann kein Iframe verwendet werden -> direkte Einbindung ins html

		if (substr($this->qlayerset[$i][GetFeatureInfoRequest],0,7) != 'http://'){
		$this->qlayerset[$i][GetFeatureInfoRequest] = 'http://'.$this->qlayerset[$i][GetFeatureInfoRequest];
		}

		$response = url_get_contents($this->qlayerset[$i][GetFeatureInfoRequest]);		
		
		if(strpos(strtolower($response), 'charset=utf-8') === false) $response = utf8_encode($response);
		$response = str_replace('css', '', $response);
		if ($response=='') {
			?><br>An dieser Position konnten zu diesem Layer keine Objekte gefunden werden.<br><?php
		}
		else {
			echo $response;
		}

	}
?>