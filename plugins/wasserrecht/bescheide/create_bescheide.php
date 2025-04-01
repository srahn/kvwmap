<?php
// require_once 'bootstrap.php';

// use PhpOffice\PhpWord\Settings;
// Settings::loadConfig();

// Set writers
// $writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');

// Set PDF renderer
// if (null === Settings::getPdfRendererPath()) {
//     $writers['PDF'] = null;
// }

// Turn output escaping on
// Settings::setOutputEscapingEnabled(true);

// use de\dvzmv\libraries\php\libdvz\GDIMV\JodDocumentConverter;

// use Dompdf\Dompdf;

// function writePDF($pdf_file, $sendPDFRequest)
// {
    
//     if($sendPDFRequest)
//     {

        /**
         * JOD Converter
         */

        //         $jod = JodDocumentConverter::createFor(KvwmapJodDocumentConverter::$default);
        //         $jod->convertFile($word_file, $pdf_file, 'application/pdf');
        
        /**
         * DOMPDF
         */
        
        //         $html =
        //         '<html><body>'.
        //         '<p>Put your html here, or generate it with your favourite '.
        //         'templating system.</p>'.
        //         '</body></html>';
        
        //         // instantiate and use the dompdf class
        //         $dompdf = new Dompdf();
        //         $dompdf->loadHtml($html);
        
        //         // (Optional) Setup the paper size and orientation
        //         $dompdf->setPaper('A4', 'landscape');
        
        //         // Render the HTML as PDF
        //         $dompdf->render();
        
        //         // Output the generated PDF to Browser
        //         $output = $dompdf->output();
        //         file_put_contents($pdf_file, $output);
        
//     }
// }

function writeWordFile(&$gui, $word_template, $word_file, &$parameter)
{
    $gui->log->log_info('*** create_bescheide->writeWordFile ***');
    $gui->log->log_debug('parameter: ' . var_export($parameter, true));
    
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($word_template);
    foreach($parameter as $key => $value)
    {
        $templateProcessor->setValue($key, $value);
    }
    $templateProcessor->saveAs($word_file);
}

function writeAufforderungZurErklaerungWordFile(&$gui, $word_template, $word_file, &$aufforderungsBescheidDaten)
{
    $gui->log->log_info('*** create_bescheide->writeAufforderungZurErklaerungWordFile ***');
    $aufforderungsBescheidDaten->toString();
    
//     writeWordFile($gui, $word_template, $word_file, $parameter);

    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($word_template);
    
    foreach($aufforderungsBescheidDaten->getParameter() as $key => $value)
    {
        $templateProcessor->setValue($key, $value);
    }
    
    $wrzs = $aufforderungsBescheidDaten->getWrzs();
    $templateProcessor->cloneRow('n', sizeof($wrzs));
    
    $i = 1;
    foreach($wrzs as $wrz)
    {
        if(!empty($wrz))
        {
            $gewaesserbenutzung = $wrz->gewaesserbenutzungen;
            
            if(!empty($gewaesserbenutzung))
            {
                $anlage_name = $wrz->anlage->getName();
                $benutzungsnummer = $gewaesserbenutzung->getKennummer();
                $aktenzeichen = $wrz->getAktenzeichen();
                $wasserbuchnummer = $gewaesserbenutzung->getWasserbuchnummer();
                if(empty($wasserbuchnummer))
                {
                    $wasserbuchnummer = "";
                }
                $datum_ausgangsbescheid = $wrz->getDatum();
                $datum_fassung = $wrz->getFassungDatum();
                if(empty($datum_fassung))
                {
                    $datum_fassung = "";
                }
                
                $templateProcessor->setValue('n#' . $i, $i);
                $templateProcessor->setValue('Benutzungsnummer#' . $i, $benutzungsnummer);
                $templateProcessor->setValue('Aktenzeichen#' . $i, $aktenzeichen);
                $templateProcessor->setValue('Wasserbuchnummer#' . $i, $wasserbuchnummer);
                $templateProcessor->setValue('Anlage_Name#' . $i, $anlage_name);
                $templateProcessor->setValue('Datum_Ausgangsbescheid#' . $i, $datum_ausgangsbescheid);
                $templateProcessor->setValue('Datum_Fassung#' . $i, $datum_fassung);
                
                $i++;
            }
        }
    }
    
    $templateProcessor->saveAs($word_file);
}

function writeFestsetzungsWordFile(&$gui, $word_template, $word_file, &$parameter, &$festsetzungsSammelbescheidDaten)
{
    $gui->log->log_info('*** create_bescheide->writeFestsetzungsWordFile ***');
    $gui->log->log_debug('parameter: ' . var_export($parameter, true));
    $festsetzungsSammelbescheidDaten->toString();
    
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($word_template);
    
    foreach($parameter as $key => $value)
    {
        $templateProcessor->setValue($key, $value);
    }
    
    $anlagen = $festsetzungsSammelbescheidDaten->getAnlagen();
    $sizeAnlagen = sizeof($anlagen);
    
    $templateProcessor->cloneRow('n1', $sizeAnlagen);
    $i = 1;
    foreach($anlagen as $anlage)
    {
        if(!empty($anlage))
        {
            $templateProcessor->setValue('n1#' . $i, $i);
            $templateProcessor->setValue('Anlage_ID1#' . $i, $anlage->getId());
            $templateProcessor->setValue('Anlage_Name1#' . $i, $anlage->getName());
            
            $i++;
        }
    }
    
    $entnahmemengen = $festsetzungsSammelbescheidDaten->getEntnahmemengen();
    $gui->log->log_debug('entnahmemengen: ' . var_export($entnahmemengen, true));
    $entgelte = $festsetzungsSammelbescheidDaten->getEntgelte();
    $gui->log->log_debug('entgelte: ' . var_export($entgelte, true), 4);
    $zugelassene_entgelte = $festsetzungsSammelbescheidDaten->getZugelassene_entgelte();
    $gui->log->log_debug('zugelassene_entgelte: ' . var_export($zugelassene_entgelte, true));
    $nicht_zugelassene_entgelte = $festsetzungsSammelbescheidDaten->getNicht_zugelassene_entgelte();
    $gui->log->log_debug('nicht_zugelassene_entgelte: ' . var_export($nicht_zugelassene_entgelte, true));
    $erlaubte_umfaenge = $festsetzungsSammelbescheidDaten->getErlaubterOderReduzierterUmfang();
    $gui->log->log_debug('erlaubte_umfaenge: ' . var_export($erlaubte_umfaenge, true));
    
    $gewaesserbenutzungen = $festsetzungsSammelbescheidDaten->getGewaesserbenutzungen();
    
    if(!empty($gewaesserbenutzungen))
    {
        $sizeTable = 0;
        
        foreach ($gewaesserbenutzungen as $gewaesserbenutzung)
        {
            if(!empty($gewaesserbenutzung))
            {
                $sizeTable = $sizeTable + 1;
                
                $teilgewaesserbenutzungen = $gewaesserbenutzung->getTeilgewaesserbenutzungenByErhebungsjahr($festsetzungsSammelbescheidDaten->getErhebungsjahr());
                if(!empty($teilgewaesserbenutzungen))
                {
                    foreach ($teilgewaesserbenutzungen as $teilgewaesserbenutzung)
                    {
                        if(!empty($teilgewaesserbenutzung))
                        {
                            $sizeTable = $sizeTable + 1;
                        }
                    }
                }
            }
        }
        
        $templateProcessor->cloneRow('n2', $sizeTable);
        $countRows = 1;
        $countGewaesserbenutzungen = 1;
        $countTeilgewaesserbenutzungen = 1;
        
        foreach ($gewaesserbenutzungen as $gewaesserbenutzung)
        {
            if(!empty($gewaesserbenutzung))
            {
                $teilgewaesserbenutzungen = $gewaesserbenutzung->getTeilgewaesserbenutzungenByErhebungsjahr($festsetzungsSammelbescheidDaten->getErhebungsjahr());
                $erlaubterUmfangBerechnet = $erlaubte_umfaenge[$countGewaesserbenutzungen - 1];
                
                if(!empty($teilgewaesserbenutzungen))
                {
                    $countTeilgewaesserbenutzungen = 1;
                    
                    foreach ($teilgewaesserbenutzungen as $teilgewaesserbenutzung)
                    {
                        if(!empty($teilgewaesserbenutzung))
                        {
                            $number = (string)$countGewaesserbenutzungen . "." . (string)$countTeilgewaesserbenutzungen;
                            $templateProcessor->setValue('n2#' . $countRows, $number);
                            $templateProcessor->setValue('Anlage_ID2#' . $countRows, "");
                            $templateProcessor->setValue('Anlage_Name2#' . $countRows, "");
                            
                            $templateProcessor->setValue('Entnamemenge#' . $countRows, FestsetzungsSammelbescheidDaten::formatNumber($teilgewaesserbenutzung->getUmfang()));
//                             $templateProcessor->setValue('Erlaubter_Umfang#' . $countRows, "");
                            $erlaubterUmfangBerechnetAlt = $erlaubterUmfangBerechnet;
                            $nichtErlaubterUmfangTeilgewaesserbenutzung = $gewaesserbenutzung->getTeilgewaesserbenutzungNichtZugelasseneMenge($festsetzungsSammelbescheidDaten->getErhebungsjahr(), $teilgewaesserbenutzung->getId(), $erlaubterUmfangBerechnet, true);
                            $templateProcessor->setValue('Erlaubter_Umfang#' . $countRows, FestsetzungsSammelbescheidDaten::formatNumber($erlaubterUmfangBerechnetAlt));
                            
                            $berechneter_entgeltsatz_zugelassen = $teilgewaesserbenutzung->getBerechneterEntgeltsatzZugelassen();
                            $berechneter_entgeltsatz_nicht_zugelassen = $teilgewaesserbenutzung->getBerechneterEntgeltsatzNichtZugelassen();
                            $templateProcessor->setValue('Entgeltsatz_Zugelassen#' . $countRows, $berechneter_entgeltsatz_zugelassen);
                            $templateProcessor->setValue('Entgeltsatz_Nicht_Zugelassen#' . $countRows, $berechneter_entgeltsatz_nicht_zugelassen);
                            
                            $berechnetes_entgelt_zugelassen = $teilgewaesserbenutzung->getBerechnetesEntgeltZugelassen();
                            $berechnetes_entgelt_nicht_zugelassen = $teilgewaesserbenutzung->getBerechnetesEntgeltNichtZugelassen();
                            $templateProcessor->setValue('Zugelassenes_Entgelt#' . $countRows, FestsetzungsSammelbescheidDaten::formatCurrencyNumber($berechnetes_entgelt_zugelassen));
                            $templateProcessor->setValue('Nicht_Zugelassenes_Entgelt#' . $countRows,  FestsetzungsSammelbescheidDaten::formatCurrencyNumber($berechnetes_entgelt_nicht_zugelassen));
                            $entgelt = $berechnetes_entgelt_zugelassen + $berechnetes_entgelt_nicht_zugelassen;
                            $templateProcessor->setValue('Entgelt#' . $countRows, FestsetzungsSammelbescheidDaten::formatCurrencyNumber($entgelt));
                            
                            $countTeilgewaesserbenutzungen++;
                            $countRows++;
                        }
                    }
                }
                
                $templateProcessor->setValue('n2#' . $countRows, $countGewaesserbenutzungen . " Summe:");
                $templateProcessor->setValue('Anlage_ID2#' . $countRows, $anlage->getId());
                $templateProcessor->setValue('Anlage_Name2#' . $countRows, $anlage->getName());
                $templateProcessor->setValue('Entnamemenge#' . $countRows, FestsetzungsSammelbescheidDaten::formatNumber($entnahmemengen[$countGewaesserbenutzungen - 1]));
                $templateProcessor->setValue('Erlaubter_Umfang#' . $countRows, FestsetzungsSammelbescheidDaten::formatNumber($erlaubte_umfaenge[$countGewaesserbenutzungen - 1]));
                $templateProcessor->setValue('Entgeltsatz_Zugelassen#' . $countRows, "");
                $templateProcessor->setValue('Entgeltsatz_Nicht_Zugelassen#' . $countRows, "");
                $templateProcessor->setValue('Zugelassenes_Entgelt#' . $countRows, FestsetzungsSammelbescheidDaten::formatCurrencyNumber($zugelassene_entgelte[$countGewaesserbenutzungen - 1]));
                $templateProcessor->setValue('Nicht_Zugelassenes_Entgelt#' . $countRows,  FestsetzungsSammelbescheidDaten::formatCurrencyNumber($nicht_zugelassene_entgelte[$countGewaesserbenutzungen - 1]));
                $templateProcessor->setValue('Entgelt#' . $countRows, FestsetzungsSammelbescheidDaten::formatCurrencyNumber($entgelte[$countGewaesserbenutzungen - 1]));
                
                $countGewaesserbenutzungen++;
                $countRows++;
            }
        }
        
        $templateProcessor->setValue('Summe_Zugelassenes_Entgelt', FestsetzungsSammelbescheidDaten::formatCurrencyNumber($festsetzungsSammelbescheidDaten->getSummeZugelasseneEntgelte()));
        $templateProcessor->setValue('Summe_Nicht_Zugelassenes_Entgelt', FestsetzungsSammelbescheidDaten::formatCurrencyNumber($festsetzungsSammelbescheidDaten->getSummeNichtZugelasseneEntgelte()));
        $templateProcessor->setValue('Summe_Entgelt', FestsetzungsSammelbescheidDaten::formatCurrencyNumber($festsetzungsSammelbescheidDaten->getSummeEntgelte()));
    }
    
    $templateProcessor->saveAs($word_file);

}