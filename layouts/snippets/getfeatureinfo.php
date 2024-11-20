<br>
<h2><?php echo $this->qlayerset[$i]['Name_or_alias']; ?></h2>
<br>
<?php
 #echo '<br>'.$this->qlayerset[$i][GetFeatureInfoRequest];
?>

<?

	if (substr($this->qlayerset[$i]['GetFeatureInfoRequest'],0,7) == substr(URL,0,7) AND $this->qlayerset[$i]['wms_auth_username'] == '') {
		# eigener Server und WMS-Server haben beide das gleiche Protokoll (beide http oder beide https)
		# und es ist keine Authentifizierung erforderlich
?>
		<iframe style="min-width:500px;width:90%;height:500px;border:none;"  src="<?php echo $this->qlayerset[$i]['GetFeatureInfoRequest']; ?>">
			Wenn Sie dies hier lesen können, unterstützt Ihr Browser keine iframes.
		</iframe><br><br>
<? 
	}
	else {
		# sie haben unterschiedliche Protokolle oder es ist Authentifizierung erforderlich und daher kann kein Iframe verwendet werden -> direkte Einbindung ins html
		$response = url_get_contents($this->qlayerset[$i]['GetFeatureInfoRequest'], $this->qlayerset[$i]['wms_auth_username'], $this->qlayerset[$i]['wms_auth_password']);		
		if (strpos(strtolower($response), 'utf-8') === false) {
			$response = utf8_encode($response);
		}
		$response = str_replace('css', '', $response);
		$response = str_replace('a href', 'a target=_blank href', $response);		# die fehlenden " sind gewollt
		if ($response=='') {
			?><br>An dieser Position konnten zu diesem Layer keine Objekte gefunden werden.<br><?php
		}
		else {
			echo $response;
		}
	}
	$this->found = 'true';
?>