<?php
// define('WASSERRECHT_DOCUMENT_PATH', SHAPEPATH . 'wasserrecht/');
// define('WASSERRECHT_DOCUMENT_URL_PATH', '/../../../data/wasserrecht/');

define('WASSERRECHT_DOCUMENT_PATH', PLUGINS . 'wasserrecht/results/');
define('WASSERRECHT_DOCUMENT_URL_PATH', '/../plugins/wasserrecht/results/');

define('WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT', 6);
define('WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_ENTGELTSATZ', 2);

define('WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE', 'bitte_auswaehlen');
define('WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT', 'Bitte auswählen!');

define('WASSERRECHT_VORDRUCK_ERKLAERUNG_WASSERENTNAHMEMENGE', 'http://www.lung.mv-regierung.de/dateien/a3_formular_wee_._Anlage_1_1pdf');

define('WASSERENTNAHMEBENUTZER', 'wasserentnahmebenutzer');
define('WASSERENTNAHMEBENUTZER_AUFFORDERUNG_ZUR_ERKLAERUNG_URL', 'wasserentnahmebenutzer_aufforderung_zur_erklaerung');
define('WASSERENTNAHMEBENUTZER_ENTGELTBESCHEID_URL', 'wasserentnahmebenutzer_entgeltbescheid');

define('WASSERENTNAHMEENTGELT_URL', 'wasserentnahmeentgelt');
define('WASSERENTNAHMEENTGELT_ERKLAERUNG_DER_ENTNAHME_URL', 'wasserentnahmeentgelt_erklaerung_der_entnahme');
define('WASSERENTNAHMEENTGELT_FESTSETZUNG_URL', 'wasserentnahmeentgelt_festsetzung');

define('ZENTRALE_STELLE_URL', 'zentrale_stelle');
define('ERSTATTUNG_DES_VERWALTUNGSAUFWANDS_URL', 'erstattung_des_verwaltungsaufwands');

define('GET_FESTSETZUNG_URL', 'getfestsetzung');
define('GET_ERKLAERUNG_URL', 'geterklaerung');
define('VERWALTUNGSAUFWAND_BEANTRAGEN_URL', 'verwaltungsaufwand_beantragen');

define('ERKLAERUNG_URL', 'erklaerung_');
define('ERKLAERUNG_ENTSPEEREN_URL', 'erklaerung_entspeeren_');
define('ERKLAERUNG_FREIGEBEN_URL', 'erklaerung_freigeben_');

define('ERHEBUNGSJAHR_URL', 'erhebungsjahr');
define('BEHOERDE_URL', 'behoerde');
define('ADRESSAT_URL', 'adressat');

define('GET_SESSION_ADRESSAT_URL', 'getAdressat');

define('ENTGELTBESCHEID_ERSTELLEN_URL', 'entgeltscheid_erstellen');
define('AUSWAHL_CHECKBOX_URL', 'auswahl_checkbox');

define('AUFFORDERUNG_CHECKBOX_URL', 'aufforderung_checkbox_');

//TEMPLATE PFADE
define('AUFFORDERUNG_BESCHEID_PATH', 'wasserrecht/templates/Aufforderung_Erklaerung.docx');
define('FESTSETZUNG_BESCHEID_PATH', 'wasserrecht/templates/Festsetzung_Sammelbescheid.docx');

//LAYER NAMES
define('SELECTED_LAYER_URL', 'Layer-Suche_Suchen&selected_layer_id');
define('BEHOERDE_LAYER_NAME', 'Behoerde');
define('BEHOERDE_LAYER_ID', 'id');
define('PERSONEN_LAYER_NAME', 'FisWrV-WRe Personen');
define('PERSONEN_LAYER_ID', 'personen_id');
define('ANLAGEN_LAYER_NAME', 'FisWrV-WRe Anlagen');
define('ANLAGEN_LAYER_ID', 'anlage_id');
define('WRZ_LAYER_NAME', 'FisWrV-WRe WrZ');
define('WRZ_LAYER_ID', 'wrz_id');
define('GEWAESSERBENUTZUNGEN_LAYER_NAME', 'FisWrV-WRe Gewässerbenutzungen');
define('GEWAESSERBENUTZUNGEN_LAYER_ID', 'gwb_id');
define('GEWAESSERBENUTZUNGEN_ART_LAYER_NAME', 'Gewaesserbenutzungen_Art');
define('GEWAESSERBENUTZUNGEN_ART_LAYER_ID', 'id');
define('GEWAESSERBENUTZUNGEN_ZWECK_LAYER_NAME', 'Gewaesserbenutzungen_Zweck');
define('GEWAESSERBENUTZUNGEN_ZWECK_LAYER_ID', 'id');
?>