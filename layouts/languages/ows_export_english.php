<?php
$strTitle="Map service Map data export";
$strWMSExportWarning="This function stores the current map displays with additional metadata of the project in a map file, so that contents in an OGC can be put to Web Map Service (WMS) independently of available kvwmap. The saving location is to be specified in the constant WMS_MAPFILE_PATH.";
$strAllOrActiveLayer="Are all layers of the task or only the active layer to be used?"; 
$strAll="All layers";
$strActiveLayer="Only activated layer";
$strNameOfMapFile="Select a name for the map file:";
$strOwsTitle ="Which service is to be designated (ows_title)";
$strOwsAbstract="Give a short description to the Web Map Service (ows_abstract):";
$strContactInfo="Contact information";
$strContactPerson="Person (ows_contactperson):";
$strOrganisation="Organisation (ows_contactorganization):";
$strEMail="E-Mail (ows_contactelectronicmailaddress):";
$strFee="Costs of the use of the service (ows_fees)";
$strExported1="The current map configuration was exported to the mapfile ".WMS_MAPFILE_PATH.$this->formvars['mapfile_name'].". The mapfile can now be used for OWS.";
$strExported2="A getCapabilities-request of this WMS looks like this";
$strExported3="An image of the map can be requested with a getMap-request, e.g.";
 
?>  