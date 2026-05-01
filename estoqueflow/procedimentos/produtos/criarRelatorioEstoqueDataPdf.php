<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ob_start(); 

require_once '../../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$dataInicio = $_POST['dataInicio'];
$dataFim = $_POST['dataFim'];

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$pdf = new Dompdf($options);

// AJUSTE: Verifique se o nome da pasta do sistema é 'sistema'
$url = "http://localhost/estoqueflow/view/produtos/relatorioEstoqueDataPdf.php?inicio=$dataInicio&fim=$dataFim";

$html = @file_get_contents($url);

if (!$html || trim($html) == "") {
    die("Erro: Não foi possível acessar o layout do relatório em: $url. Verifique se o arquivo view/produtos/relatorioEstoqueDataPdf.php existe.");
}

$pdf->loadHtml(trim($html));
$pdf->setPaper("letter", "portrait");
$pdf->render();

ob_end_clean(); 
$pdf->stream('Estoque_Por_Periodo.pdf', array("Attachment" => false));