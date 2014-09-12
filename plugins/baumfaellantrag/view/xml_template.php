<?php
$xml_string = 
'<?xml version="1.0" encoding="UTF-8"?>
<xml-data xmlns="http://www.lucom.com/ffw/xml-data-1.0.xsd">
	<form>catalog://kommunen/zweck/zweckverb_antrag_faellung</form>
	<instance>
		<datarow>
			<element id="ID_PROVIDER">' . $data['provider_id'] . '</element>
			<element id="ID_USER">MANDANTUSER</element>
			<element id="LIP_FORM_REVISION">-1</element>
			<element id="DVZ_FORM_NAME">Antrag auf Fällung von Einzelbäumen</element>
			<element id="DVZ_M_ID">' . $antrag_id . '</element>
			<element id="DVZ_FORM_ID">zweckverb_antrag_faellung</element>
			<element id="DVZ_ID_USER">MD000_USR001</element>
			<element id="DVZ_ID_GROUP">FMS_DEMO</element>
			<element id="DVZ_STATUS">1</element>
			<element id="ZWECK_NAME">' . $data['surname'] . '</element>
			<element id="ZWECK_VORNAME">' . $data['forename'] . '</element>
			<element id="ZWECK_STRASSE_HNR">' . $data['streetName'] . ' ' . $data['streetNo'] . '</element>
			<element id="ZWECK_PLZ">' . $data['postcode'] . '</element>
			<element id="ZWECK_ORT">' . $data['place'] . '</element>
			<element id="ZWECK_TELEFON">' . $data['phone'] . '</element>
			<element id="ZWECK_FAX">' . $data['fax'] . '</element>
			<element id="ZWECK_EMAIL">' . $data['email'] . '</element>
			<element id="ZWECK_ANTRAGSTELLER_IST">' . $data['ownerinfo'] . '</element>
			<element id="ZWECK_VOLLMACHT">' . $data['mandateReference'] . '</element>
			<element id="ZWECK_STANDORT_LAND_ID">' . $data['cadastre_stateId'] . '</element>
			<element id="ZWECK_STANDORT_KREIS_ID">' . $data['cadastre_districtId'] . '</element>
			<element id="ZWECK_STANDORT_GEMEINDE_ID">' . $data['cadastre_municipalityId'] . '</element>
			<element id="ZWECK_STANDORT_GEMEINDE_NAME">' . $data['cadastre_municipalityName'] . '</element>
			<element id="ZWECK_STANDORT_GEMARKUNG_ID">' . $data['cadastre_boundaryId'] . '</element>
			<element id="ZWECK_STANDORT_GEMARKUNG_NAME">' . $data['cadastre_boundaryName'] . '</element>
			<element id="ZWECK_STANDORT_FLUR_ID">' . $data['cadastre_sectionId'] . '</element>
			<element id="ZWECK_STANDORT_FLURSTUECK_ID">' . $data['cadastre_parcelId'] . '</element>
			<element id="ZWECK_STANDORT_FLURSTUECK_NUMMER">' . $data['cadastre_parcelNo'] . '</element>
			';
$xml_string .= '
			<element id="ZWECK_STANDORT_SATZUNGSGEBIET_ID">' . $data['statute_id'] . '</element>
			<element id="ZWECK_STANDORT_SATZUNGSGEBIET_NAME">' . $data['statute_name'] . '</element>
			<element id="ZWECK_STANDORT_SATZUNGSGEBIET_TYPE">' . $data['statute_type'] . '</element>
			<element id="ZWECK_STANDORT_SATZUNGSGEBIET_ERLAUBTER_DURCHMESSER">' . $data['statute_allowedDiameter'] . '</element>
			';
$xml_string .= '
			<element id="DVZ_EMPF_MANDANT_NUMMER">' . $data['authority_municipalityNr'] . '</element>
			<element id="DVZ_EMPF_MANDANT">' . $data['authority_municipalityName'] . '</element>
			<element id="DVZ_EMPF_MANDANT_ZUSATZ">' . $data['authority_districtNr'] . '</element>
			<element id="DVZ_EMPF_SACHGEB">Baumfällgenehmigungen</element>
			';
$xml_string .= '<element id="DVZ_EMPF_STRASSE"></element>
			<element id="DVZ_EMPF_PLZ">' . $data['DVZ_EMPF_PLZ'] . '</element>
			<element id="DVZ_EMPF_ORT">' . $data['DVZ_EMPF_ORT'] . '</element>
			';
$xml_string .= '<element id="DVZ_EMPF_CONTACT_PERSON">' . $data['authority_contactPerson'] . '</element>
			';
$xml_string .= '<element id="DVZ_EMPF_EMAIL">' . $data['authority_email'] . '</element>
			';
$xml_string .= '<element id="DVZ_EMPF_BEARBEITUNGSZEIT">' . $data['authority_processingTime'] . '</element>
			';
$xml_string .= '<element id="ZWECK_BAUM_BILD">' . $data['locationSketchReference'] . '</element>
			';
			for ($i = 0; $i < count ( $data['wood_species'] ); $i++ ) {
				$nr = $i + 1;
$xml_string .= '<element id="ZWECK_BAUM_LATITUDE_'. $nr . '">' . $data['latitude'][$i] . '</element>
			<element id="ZWECK_BAUM_LONGITUDE_'. $nr . '">' . $data['longitude'][$i] . '</element>
			<element id="ZWECK_BAUM_ART_'. $nr . '">' . $data['wood_species'][$i] . '</element>
			<element id="ZWECK_BAUM_UMFANG_1.3_'. $nr . '">' . $data['trunk_circumfence'][$i] . '</element>
			<element id="ZWECK_BAUM_KRONENDURCHMESSER_'. $nr . '">' . $data['crown_diameter'][$i] . '</element>
			';
#				$xml_string .= '<element id="Baumbild_'. $i . '">' . $antrag_id . '_Baum_' . $i . "_Bild_"  . basename($data['treeImage'][$i]) . '</element>\r\n';
			}
			$xml_string .= '
		</datarow>
	</instance>
</xml-data>';
?>