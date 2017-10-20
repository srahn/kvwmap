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
    $gui->debug->write('*** create_bescheide->writeWordFile ***', 4);
    $gui->debug->write('parameter: ' . var_export($parameter, true), 4);
    
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($word_template);
    foreach($parameter as $key => $value)
    {
        $templateProcessor->setValue($key, $value);
    }
    $templateProcessor->saveAs($word_file);
}

function writeAufforderungZurErklaerungWordFile(&$gui, $word_template, $word_file, &$parameter)
{
    $gui->debug->write('*** create_bescheide->writeAufforderungZurErklaerungWordFile ***', 4);
    $gui->debug->write('parameter: ' . var_export($parameter, true), 4);
    
    writeWordFile($gui, $word_template, $word_file, $parameter);
}

function writeFestsetzungsWordFile(&$gui, $word_template, $word_file, &$parameter, &$festsetzungsSammelbescheidDaten)
{
    $gui->debug->write('*** create_bescheide->writeFestsetzungsWordFile ***', 4);
    $gui->debug->write('parameter: ' . var_export($parameter, true), 4);
    $festsetzungsSammelbescheidDaten->toString();
    
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($word_template);
    foreach($parameter as $key => $value)
    {
        $templateProcessor->setValue($key, $value);
    }
    
    $anlagen = $festsetzungsSammelbescheidDaten->getAnlagen();
    
    $templateProcessor->cloneRow('n1', sizeof($anlagen));
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
    $entgelte = $festsetzungsSammelbescheidDaten->getEntgelte();
    $zugelassene_entgelt = $festsetzungsSammelbescheidDaten->getZugelassene_entgelte();
    $nicht_zugelassene_entgelt = $festsetzungsSammelbescheidDaten->getNicht_zugelassene_entgelte();
    $erlaubter_umfang = $festsetzungsSammelbescheidDaten->getErlaubterUmfang();
    
    $templateProcessor->cloneRow('n2', sizeof($anlagen));
    $i = 1;
    foreach($anlagen as $anlage)
    {
        if(!empty($anlage))
        {
            $templateProcessor->setValue('n2#' . $i, $i);
            $templateProcessor->setValue('Anlage_ID2#' . $i, $anlage->getId());
            $templateProcessor->setValue('Anlage_Name2#' . $i, $anlage->getName());
            $templateProcessor->setValue('Entnamemenge#' . $i, FestsetzungsSammelbescheidDaten::formatNumber($entnahmemengen[$i - 1]));
            $templateProcessor->setValue('Erlaubter_Umfang#' . $i, FestsetzungsSammelbescheidDaten::formatNumber($erlaubter_umfang[$i - 1]));
            $templateProcessor->setValue('Zugelassenes_Entgelt#' . $i, FestsetzungsSammelbescheidDaten::formatCurrencyNumber($zugelassene_entgelt[$i - 1]));
            $templateProcessor->setValue('Nicht_Zugelassenes_Entgelt#' . $i,  FestsetzungsSammelbescheidDaten::formatCurrencyNumber($nicht_zugelassene_entgelt[$i - 1]));
            $templateProcessor->setValue('Entgelt#' . $i, FestsetzungsSammelbescheidDaten::formatCurrencyNumber($entgelte[$i - 1]));
            
            $i++;
        }
    }
    
    $templateProcessor->setValue('Summe_Zugelassenes_Entgelt', FestsetzungsSammelbescheidDaten::formatCurrencyNumber($festsetzungsSammelbescheidDaten->getSummeZugelasseneEntgelte()));
    $templateProcessor->setValue('Summe_Nicht_Zugelassenes_Entgelt', FestsetzungsSammelbescheidDaten::formatCurrencyNumber($festsetzungsSammelbescheidDaten->getSummeNichtZugelasseneEntgelte()));
    $templateProcessor->setValue('Summe_Entgelt', FestsetzungsSammelbescheidDaten::formatCurrencyNumber($festsetzungsSammelbescheidDaten->getSummeEntgelte()));
    
    $templateProcessor->saveAs($word_file);

}