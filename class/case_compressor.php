<?

# value_of muss hier nochmal unter anderem Namen definiert werden, damit man sie in der case_compressor Klasse verwenden kann
# die Verwendung von value_of w�rde sonst eine unendliche Rekursion erzeugen
function value_of2($array, $key) {
	return (array_key_exists($key, $array) ? $array[$key] :	'');
}

class case_compressor {

	public static $filearray = array();
	public static $classarray = array();
	public static $nonclassfunctionstring = '';
	public static $nonclassfunctionarray = array();

	public static function extract_code($functionname){		
		try{
			$func = new ReflectionMethod($functionname);		// es ist eine Funktion einer Klasse
			# den Klassennamen ermitteln
			$class = $func->getDeclaringClass();
			$classname = $class->getName();
			if($parent = $class->getParentClass()){
				$extends = ' extends '.$parent->getName();
			}
			else{
				$extends = '';
			}
			$properties = $class->getProperties();
			
			# wenn er noch nicht da ist, den leeren Klassenk�rper ermitteln
			if(value_of2(self::$classarray, $classname) == ''){
				self::$classarray[$classname]['code'] = chr(10).'class '.$classname.$extends.' {'.chr(10);		
				foreach($properties as $prop) {
					if($prop->isStatic())$var = 'static'; else $var = 'var';
					#if($prop->isPublic())$public = 'public '; else $public = '';
					self::$classarray[$classname]['code'] .= chr(10).'  '.$var.' $'.$prop->getName();
					if(@$prop->getValue() != '')self::$classarray[$classname]['code'] .= ' = '.@$prop->getValue().';';
					else self::$classarray[$classname]['code'] .= ';';
				}
				self::$classarray[$classname]['code'] .= chr(10);
			}
			# wenn er noch nicht da ist, den Funktionscode ermitteln und zum Klassen-Array hinzuf�gen
			if(value_of2(self::$classarray[$classname], $functionname) != true){
				self::$classarray[$classname]['code'] .= chr(10).self::get_function_body($func);
				self::$classarray[$classname][$functionname] = true;	// merken, dass Funktion schon extrahiert wurde
			}
		}
		catch(Exception $e){		// es ist eine Funktion ohne Klasse
			# wenn er noch nicht da ist, den Funktionscode ermitteln und zum nonclassfunction-String hinzuf�gen
			if($functionname != '{closure}')
			if(value_of2(self::$nonclassfunctionarray, $functionname) != true){
				$func = new ReflectionFunction($functionname);				
				self::$nonclassfunctionstring .= chr(10).self::get_function_body($func);
				self::$nonclassfunctionarray[$functionname] = true;		// merken, dass Funktion schon extrahiert wurde
			}
		}		
	}
	
	public static function get_function_body($func){
		$filename = $func->getFileName();
		$start_line = $func->getStartLine() - 1;
		$end_line = $func->getEndLine();
		$length = $end_line - $start_line;
		$originalfilename = str_replace('_injected', '', $filename);
		$source = self::$filearray[$originalfilename];
		return implode("", array_slice($source, $start_line, $length));
	}

	public static function write_fast_case_file($go){
		$fast_case_classfile = CLASSPATH.'fast_cases/'.$go.'.php';
		$handle = fopen($fast_case_classfile, "w+");
		fwrite($handle, '<?'.chr(10));
		fwrite($handle, self::$nonclassfunctionstring.chr(10));
		foreach(self::$classarray as $class){	
			fwrite($handle, $class['code'].'}'.chr(10));
		}
		fwrite($handle, '?>'.chr(10));
		fclose($handle);		
	}

	public static function inject($filename){
		$filenameparts = explode('.', $filename);
		$type = array_pop($filenameparts);
		$file = implode('.', $filenameparts);
		$newfilename = $file.'_injected.'.$type;
		$filestring = file_get_contents($filename);
		self::$filearray[$filename] = file($filename);
		# die original Klassendateien mit dem Code-Extraktor injezieren
		$filestring = preg_replace('/function .+{/', '$0 case_compressor::extract_code(__METHOD__);', $filestring);
		$handle = fopen($newfilename, "w");
		fwrite($handle, $filestring);
		fclose($handle);
		return $newfilename;
	}

}

?>