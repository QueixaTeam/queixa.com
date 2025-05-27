<?php 
require __DIR__ . '/vendor/autoload.php'; 
use Spipu\Html2Pdf\Html2Pdf; 
use Spipu\Html2Pdf\Exception\Html2PdfException; 
use Spipu\Html2Pdf\Exception\ExceptionFormatter; 

$tipo = $_GET['tipo'] ?? 'categoria'; // tipo recebido via URL 

$arquivosValidos = [
    'queixasProduto' => 'contentQueixasProduto.php',
    'MediaNotaProd'   => 'contentMediaNotaProd.php',
    'ProdMelhorQueixa'   => 'contentProdMelhorQueixa.php',
    'ProdPiorQueixa'  => 'contentProdPiorQueixa.php'
];

if (!isset($arquivosValidos[$tipo])) {
    die("Relatório inválido.");
}

try { 
    ob_start(); 
    include __DIR__ . '/' . $arquivosValidos[$tipo]; 
    $content = ob_get_clean(); 
    $html2pdf = new Html2Pdf('P', 'A4', 'pt'); 
    $html2pdf->pdf->SetDisplayMode('fullpage'); 
    $html2pdf->writeHTML($content); 
    $html2pdf->output("relatorio_$tipo.pdf"); 
} catch (Html2PdfException $e) { 
    $html2pdf->clean(); 
    $formatter = new ExceptionFormatter($e); 
    echo $formatter->getHtmlMessage(); 
}
