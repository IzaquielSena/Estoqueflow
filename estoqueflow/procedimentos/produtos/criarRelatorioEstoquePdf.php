<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ob_start(); 

require_once '../../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$pdf = new Dompdf($options);

// Verifique se o nome da pasta é exatamente 'sistema'
$url = "http://localhost/estoqueflow/view/produtos/relatorioEstoquePdf.php";
$html = @file_get_contents($url);

if (!$html || trim($html) == "") {
    die("Erro: O conteúdo do layout está vazio ou a URL é inacessível. Verifique se o arquivo existe em: " . $url);
}

$pdf->loadHtml(trim($html));
$pdf->setPaper("letter", "portrait");
$pdf->render();

ob_end_clean(); 
$pdf->stream('Estoque_Geral.pdf', array("Attachment" => false));