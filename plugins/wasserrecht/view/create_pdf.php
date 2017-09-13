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

function writeAufforderungsWordFile($word_template, $word_file)
{
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($word_template);
    $templateProcessor->setValue('PLZ', '19053');
    $timestamp = time();
    $datum = date("d.m.Y", $timestamp);
    $templateProcessor->setValue('datum', $datum);
    $templateProcessor->saveAs($word_file);
}

function writeFestsetzungsWordFile($word_template, $word_file, $festsetzungsNutzer, $festsetzungsFreitext)
{
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($word_template);
    $templateProcessor->setValue('festsetzung_nutzer', $festsetzungsNutzer);
    $templateProcessor->setValue('festsetzung_freitext', $festsetzungsFreitext);
//     $timestamp = time();
//     $datum = date("d.m.Y", $timestamp);
//     $templateProcessor->setValue('datum', $datum);
    $templateProcessor->saveAs($word_file);
}