<?

# Test der Verbindung zur MySQL-DB
$check['mysql_connection'] = $GUI->database->open();
$output[] = 'Verbindung zur MySQL-DB: ' . ($check['mysql_connection']? 'ok' : 'fehlgeschlagen');

# Test der Verbindung zur PostgreSQL-DB
$GUI->pgdatabase = new pgdatabase();
$check['postgres_connection'] = $GUI->pgdatabase->open(POSTGRES_CONNECTION_ID);
$output[] = 'Verbindung zur PostgreSQL-DB: ' . ($check['postgres_connection']? 'ok' : 'fehlgeschlagen');

# Test ob Mapserver Karte rendern kann
set_error_handler("MapserverErrorHandler");
$map = new mapObj(DEFAULTMAPFILE);
$map->setSymbolSet(SYMBOLSET);
$map->setFontSet(FONTSET);
$map->set('width', 300);
$map->set('height', 200);
$map->setextent(12.121, 54.09, 12.124, 54.091);
$layer = ms_newLayerObj($map);
$layer->updateFromString('
LAYER
  NAME "test"
  TYPE POINT
  STATUS ON
  FEATURE
    POINTS
      12.12244 54.09063
    END
  END  
  CLASS
    STYLE
      COLOR 0 0 250
      SYMBOL "circle"
      SIZE 10
    END
		TEXT \'Test\'
		LABEL
			FONT "arial"
			SIZE 11
			OFFSET 0 0
			OUTLINECOLOR 255 255 255
			POSITION CC
			SHADOWSIZE 1 1
		END
  END
END
');
$image_map = $map->draw();
$filename = rand(0, 1000000).'.'.$map->outputformat->extension;
$image_map->saveImage(IMAGEPATH . $filename);
$check['map_rendering'] = filesize(IMAGEPATH . $image) > 0;
$output[] = 'Rendern einer Karte: ' . ($check['map_rendering']? 'ok' : 'fehlgeschlagen');

# Ausgabe Ergebnis
if (in_array(false, $check)){
	echo 'health check failed';
}
else {
	echo 'health check passed';
}

echo '<br><br>' . implode('<br>', $output);

?>