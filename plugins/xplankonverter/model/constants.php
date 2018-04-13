<?php
  // database schemes
	define('CONTENT_SCHEME', 'xplan_gml');
	define('STRUCTURE_SCHEME', 'xplan_uml');

	// Konstanten fuer GML-Builder
	// XML-namespace
	define('XPLAN_NS_PREFIX', 'xplan');
	define('XPLAN_NS_URI', "http://www.xplanung.de/xplangml/5/0");
	define('XPLAN_NS_SCHEMA_LOCATION', "http://www.xplanungwiki.de/upload/XPlanGML/5.0/Schema/XPlanung-Operationen.xsd");
	// max Rekursionstiefe für Nested Composite Types
	define ('XPLAN_MAX_NESTING_DEPTH', 3);
?>