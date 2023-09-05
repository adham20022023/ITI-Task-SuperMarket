<?php 
session_start();
use Mpdf\Mpdf;

require_once __DIR__ . '/vendor/autoload.php';

    function print_table($table){
        $mpdf = new Mpdf(['mode'=>'utf-8']);
        $mpdf->autoScriptToLang=true;
        $mpdf->autoLangToFont=true;
        $mpdf->WriteHTML($table);
        $pdfFilename = 'invoice.pdf';
        $mpdf->Output($pdfFilename, 'F');

    }
?>