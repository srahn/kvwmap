<br>
<h2><?php echo $this->qlayerset[$i]['Name']; ?></h2>
<br>
<?php
 #echo '<br>'.$this->qlayerset[$i][GetFeatureInfoRequest];

	$response = url_get_contents($this->qlayerset[$i]['GetFeatureInfoRequest'], $this->qlayerset[$i]['wms_auth_username'], $this->qlayerset[$i]['wms_auth_password']);		
	
	if(strpos(strtolower($response), 'utf-8') === false) $response = utf8_encode($response);
	$response = str_replace('css', '', $response);
	if ($response=='') {
		?><br>An dieser Position konnten zu diesem Layer keine Objekte gefunden werden.<br><?php
	}
	else {
		echo $response;
	}
?>