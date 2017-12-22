<?php
  define('XPLANKONVERTER_FILE_PATH', UPLOADPATH . 'xplankonverter/');
  define('XPLANKONVERTER_SHAPE_PATH', UPLOADPATH . 'xplanshapes/');
  define('XPLANKONVERTER_KONVERTIERUNGEN_LAYER_ID', 8);
  define('XPLANKONVERTER_SHAPEFILES_LAYER_ID', 10);
  define('XPLANKONVERTER_PLAENE_LAYER_ID', 2);
  define('XPLANKONVERTER_BEREICHE_LAYER_ID', 3);
  define('XPLANKONVERTER_REGELN_LAYER_ID', 9);
	define('XPLANKONVERTER_VALIDIERUNGSERGEBNISSE_LAYER_ID' , 455);
	define('GML_LAYER_TEMPLATE_GROUP', 10004);

	define('XPLANKONVERTER_CONTENT_SCHEMA', 'xplan_gml');

	// Konstanten fuer GML-Builder
	// XML-namespace
	define('XPLAN_NS_PREFIX', 'xplan');
	define('XPLAN_NS_URI', "http://www.xplanung.de/xplangml/5/0");
	define('XPLAN_NS_SCHEMA_LOCATION', "http://www.xplanungwiki.de/upload/XPlanGML/5.0/Schema/XPlanung-Operationen.xsd");
	// max Rekursionstiefe für Nested Composite Types
	define ('XPLAN_MAX_NESTING_DEPTH', 3);
?>