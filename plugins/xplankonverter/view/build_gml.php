<br><div style="width:100%;overflow:scroll;"><h1>XPlanGML Konverter</h1><pre style="text-align: left;">
<?php
  // use the DOMDocument functionality to format XML output
  $dom = new DOMDocument('1.0');
  $dom->preserveWhiteSpace = FALSE;
  $dom->formatOutput = TRUE;
  $dom->loadXML($this->gml_builder->build_gml());
  echo htmlentities($dom->saveXML());
//   echo htmlentities($this->gml_builder->build_gml());
  ?>
</pre></div>