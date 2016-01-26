<br><div style="width:100%;overflow:scroll;"><h1>XPlanGML Konverter</h1><pre style="text-align: left;">
<?php
//   echo str_replace('<', '&lt;', str_replace('>', '&gt;', $this->gml_builder->build_gml()));
  // format XML output
  $dom = new DOMDocument('1.0');
  $dom->preserveWhiteSpace = FALSE;
  $dom->formatOutput = TRUE;
  $dom->loadXML($this->gml_builder->build_gml());
  echo htmlentities($dom->saveXML());
  ?>
</pre></div>