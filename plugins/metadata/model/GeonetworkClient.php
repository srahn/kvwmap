<?php
class GeonetworkClient {
    
	public function __construct($CATALOG, $CATALOGUSER, $CATALOGPASS) {
        $this->CATALOG = $CATALOG;
        $this->CATALOGUSER = $CATALOGUSER;
		$this->CATALOGPASS = $CATALOGPASS;
		$this->ch = null;
    }

    public function connect() {
		#echo("connecting: ". $this->CATALOG);
		$this->ch = curl_init();	
		
		$ch = $this->ch;
				
		// $url01 = "$this->CATALOG/srv/eng/info?type=me";
		$url01 = "$this->CATALOG/srv/api/0.1/me";
		$url01 = "https://mis.testportal-plandigital.de/geonetwork/srv/api/0.1/me";
		$this->tokenHeader = "X-XSRF-TOKEN: ";
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			$this->tokenHeader,
			'accept: application/json'
		));
		
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_URL, $url01);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$curlResult = curl_exec($ch);	
	
		$httpCode = curl_getinfo($ch , CURLINFO_HTTP_CODE);	
		$corlErrNo = curl_errno($ch);	
		$curlError = curl_error($ch);
	
		$idx01 = strpos($curlResult, 'XSRF-TOKEN=') + strlen("XSRF-TOKEN=");
		$idx02 = strpos($curlResult, ';', $idx01);
		$this->token = substr($curlResult, $idx01, $idx02 - $idx01);
	
	
		$this->curlTextConnect = "curlExcuted ${url01}\nhttpCode=${httpCode}\ncurlError=\"${curlError}\"\nToken=$this->token\ncurlResult=\"${curlResult}\"";
		
		$this->tokenHeader = "X-XSRF-TOKEN: $this->token";
		
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			$this->tokenHeader,
			'Authorization: Basic '. base64_encode($this->CATALOGUSER . ":" . $this->CATALOGPASS),
			'cookie: XSRF-TOKEN=' .  $this->token,
			'accept: application/json'
		));
		$curlResult = curl_exec($ch);
		$httpCode = curl_getinfo($ch , CURLINFO_HTTP_CODE);	
		$corlErrNo = curl_errno($ch);	
		$curlError = curl_error($ch);	
		$this->curlTextInit = "curlExcuted ${url01}\nhttpCode=${httpCode}\ncurlError=\"${curlError}\"\ncurlResult=\"${curlResult}\"";
        
		$result = json_decode($curlResult);
		// echo "<textarea rows=\"10\" style=\"width:100%; background-color:white\">". $curlResult . "</textarea>";
		return $result;
    }
	/*
	 *	uuidProcessing = GENERATEUUID, NOTHING, OVERWRITE
	 *		GENERATEUUID erzeugt neue UUID und amit immer neuen Datensatz
	 *		NOTHING legt den Datensatz mit der UUID an oder erzeugt Fehler, wenn Datensatz mit UUID existiert
	 *      OVERWRITE überschreibt den Datensatz mit der UUID
	 *
	 */	 
	public function putMetaData(string $xml, string $uuidProcessing = 'NOTHING') {
		$ch = $this->ch;
		$urlPut = "$this->CATALOG/srv/api/0.1/records?metadataType=METADATA&recursiveSearch=false&publishToAll=true&assignToCatalog=false&uuidProcessing=${uuidProcessing}&rejectIfInvalid=false&transformWith=_none_";
	
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			$this->tokenHeader,
			'Authorization: Basic '. base64_encode($this->CATALOGUSER . ":" . $this->CATALOGPASS),
			'cookie: XSRF-TOKEN=' .  $this->token,
			'accept: application/json'
		));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_URL, $urlPut);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml );
		$curlResult = curl_exec($ch);
		$httpCode = curl_getinfo($ch , CURLINFO_HTTP_CODE);	
		$corlErrNo = curl_errno($ch);	
		$curlError = curl_error($ch);	
		$curlText = "curlPutExcuted ${urlPut}\nhttpCode=${httpCode}\ncurlError=\"${curlError}\"\ncurlResult=\"${curlResult}\"";	
		
		$result = json_decode($curlResult);
		$result->success = ($httpCode>=200 & $httpCode<300);
		
		// echo "<textarea rows=\"10\" style=\"width:100%; background-color:white\">". $httpCode . "</textarea>";
		return $result;
	}
	
	public function close() {
		curl_close($this->ch);
	}
}
?>