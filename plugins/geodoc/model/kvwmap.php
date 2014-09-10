<?

	$GUI = $this;

	$this->spatialDocIndexing = function() use ($GUI){
    $doc=new textdocument($GUI->pgdatabase);
    $ret=$doc->spatialDocIndexing("/srv/www/var/data/SpatialDoc/","Adressen_Katasteraemter.pdf",false,true);
    #$test = $doc->pdf2string("/srv/www/test/Adressen_Katasteraemter.pdf");
    return $ret;
  }

?>