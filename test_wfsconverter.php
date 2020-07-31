<?PHP 
?>
<!DOCTYPE html>
<html>
<body>

<h1>WFS Converter test</h1>
<p>This testclass reads a WFS-query into a database and tries to convert it into an existing XPlanung-DB</p>

<!--ogr2ogr -lco GEOMETRY_NAME=msGeometry -overwrite f "PostgreSQL" -a_srs EPSG:25833 PG:"host=85.214.52.246 dbname=kvwmapsp user=kvwmap password=KatzaTo1 schemas=wfs_test"  -nln schema.table
"WFS:https://service.btfietz.de/dienste/rosheide?service=WFS&version=1.1.0&request=GetFeature&typename=B_Plan&srsName=EPSG:25833" -nlt PROMOTE_TO_MULTI-->
<!--
ogr2ogr -f "PostgreSQL" -skipfailures PG:"host=85.214.52.246 port=5432 dbname=kvwmapsp user=kvwmap password=KatzaTo1 schemas=wfs_test" "WFS:https://service.btfietz.de/dienste/rosheide?service=WFS&version=1.1.0&request=GetFeature&typename=B_Plan&srsName=EPSG:25833" -nlt PROMOTE_TO_MULTI -oo EXPOSDE_GML_ID=YES -overwrite
-->
<?PHP
$testURL = "https://service.btfietz.de/dienste/rosheide?service=WFS&version=1.1.0&request=GetFeature&typename=B_Plan";
$wfsConverter = new WFSConverter();
$wfsConverter->searchForUpdates($testURL);
$wfsConverter->insertFromSourceIntoTargetTable();
?>
</body>
</html>

<?PHP
// TODO if this becomes a class in kvwmap, put defines into config class (or use existing ones)

class WFSConverter {
	
	function __construct(/*$database*/) {
		//TODO replace this in use with $this->pgdatabase->host within kvwmap
		//$this->pgdatabase = $database;
		$this->host = '85.214.52.246';
		$this->port = '5432';
		$this->dbName = 'kvwmapsp';
		$this->user = 'kvwmap';
		$this->passwd = 'KatzaTo1';
		$this->wfsDbSchema = 'wfs_test';
		$this->wfsDbTable = 'bp_plan';
		$this->konverterSchema = 'xplan_gml';
		$this->konverterTable = 'bp_plan';
		$this->wfsGetFeatureLocation = '';
		$this->docker_gdal_cmd = 'docker exec gdal';
		$this->OGR_BINPATH_GDAL = '/usr/local/gdal/bin/'; //TODO get this from config defined const instead
		$this->mappingTable = [
			"id" => "gml_id",
			"msGeometry" => "raeumlichergeltungsbereich", // should already be multi on source with GDAL -nlt PROMOTE_TO_MULTI
			"name" => "name",
			"nummer" => "nummer",
			"planart::xplan_gml.bp_planart[]" => "planart",
			"rechtsstand::xplan_gml.bp_rechtsstand" => "rechtsstand",
			"inkrafttretensdatum" => "inkrafttretensdatum",
			"gemeinde::xplan_gml.xp_gemeinde[]" => "gemeinde",
			"externereferenz:.xplan_gml.xp_spezexternereferenz[]" => "externereferenz"
		];
	}

	private $existingGml = [];

	/*
	* Entryfunction to search for updates under a specified URL
	* TODO consider using a preliminary HTTP If-modified-since header to check possible filechanges before downloading/parsing
	* See e.g.: https://stackoverflow.com/questions/10847157/handling-if-modified-since-header-in-a-php-script
	*/
	function searchForUpdates($searchedUrl) {
		$this->wfsGetFeatureLocation = $searchedUrl;
		$output = $this->writeIntoDBIntoDB();
		if(!empty($output) {
			echo $output;
		}
	}

	/*
	* Writes data into DB
	* ogr2ogr function to load a gml file into a new db
	*/
	function writeIntoDBIntoDB() {
		# For Logging add: . ' >> /var/www/logs/ogr_' . $gml_id . '.log 2>> /var/www/logs/ogr_' . $gml_id . '.err'
		//ogr2ogr -f "PostgreSQL" PG:"host=85.214.52.246 port=5432 dbname=kvwmapsp_dev user=kvwmap password=KatzaTo1 schemas=wfs_test" "WFS:https://service.btfietz.de/dienste/rosheide?service=WFS&version=1.1.0&request=GetFeature&typename=B_Plan" -oo EXPOSE_GML_ID=YES -nlt PROMOTE_TO_MULTI -overwrite
		$cmd = $this->docker_gdal_cmd . ' ' . $this->OGR_BINPATH_GDAL . 'ogr2ogr -f "PostgreSQL" PG:"host=' . $this->host . ' port=' . $this->port . ' dbname=' . $this->dbName . ' user=' . $this->user . ' password=' . $this->passwd . ' schemas=' . $this->wfsDbSchema .'" "WFS:' . $this->wfsGetFeatureLocation . '" -oo EXPOSE_GML_ID=YES -nlt PROMOTE_TO_MULTI -overwrite';
		echo $cmd;
		exec($cmd, $output, $error_code);
		echo '<pre>'; print_r($output); echo '</pre>';
		echo 'Error-Code:' . $error_code;
		return $output;
	}

	/*
	*
	*/
	function insertFromSourceIntoTargetTable() {
		$sql  = "INSERT INTO " . $this->konverterSchema . "." . $this->konverterTable . "(";
		foreach($this->mappingTable as $srcAttr => $tarAttr) {
			$sql .= $tarAttr . ",";
		}
		// cut comma
		$sql = rtrim($sql);
		$sql .= ") ";
		foreach($this->mappingTable as $srcAttr => $tarAttr) {
			$sql .= "src." . $srcAttr . " AS " . $tarAttr . ",";
		}
		$sql = rtrim($sql);
		// space required, src as alias
		$sql .= " FROM " . $this->wfsDbSchema . "." . $this->wfsDbTable . " src;"
		echo $sql;
	}

	/*
	* Checks whether the link is valid by testing:
	* -whether the parameter is a string
	* -whether the file can be accessed
	* -whether the content can be parsed as XML
	* - TODO Check sourceEncoding
	*/
	function checkIfLinkIsValid($urlTovalidate) {
		$isValid = true;
		try{
			if(!is_string($urlTovalidate) || !filter_var($urlTovalidate, FILTER_VALIDATE_URL)) {
				$isValid = false;
				throw new InvalidArgumentException('Argument is not a string');
			}
		}
		catch(Exception $e) {
			echo 'Message: ' .$e->getMessage();
		}
		try {
			if(!simplexml_load_string(file_get_contents($urlTovalidate))) {
				$isValid = false;
				throw new InvalidArgumentException('Argument not available or not valid XML');
			}
		}
		catch(Exception $e) {
			echo 'Message: ' . $e->getMessage();
		}
		return $isValid;
	}

	/*
	* Returns a string array of $attributes of specified class
	* The function ignores namespaces of classes and attributes
	* if this is a concern (e.g. a gml:id and a ms:id attribute appear, use a namespace-specific XML-Dom function 
	*/
	function getAttributeValuesOfXML($xml, $className, $attributeName) {
		$values = [];
		foreach($xml->getElementsByTagName($className) AS $planClass) {
			if(!($planClass->hasAttributes())) {
				continue;
			}
			foreach($planClass->attributes as $attribute) {
				if($attribute->name == $attributeName) {
					$gml_ids[] = $attribute->nodeValue;
				}
			}
		}
		return $values;
	}
}

?>