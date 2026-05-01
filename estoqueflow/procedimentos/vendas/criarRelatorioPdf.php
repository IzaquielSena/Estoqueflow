<?php
ob_start();

require_once '../../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$codigovenda = isset($_GET['codigovenda']) ? $_GET['codigovenda'] : null;

if (!$codigovenda) {
    die("Código da venda não fornecido.");
}

function buscar_relatorio($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $dados = curl_exec($ch);
    curl_close($ch);
    return $dados;
}

$url = "http://localhost/estoqueflow/view/vendas/relatorioVendaPdf.php?codigovenda=" . $codigovenda;
$html = buscar_relatorio($url);

if (!$html) {
    ob_end_clean();
    die("Erro ao capturar o HTML do relatório.");
}

$pdf = new Dompdf();
$pdf->setPaper("letter", "portrait"); 
$pdf->loadHtml($html);
$pdf->render();

ob_end_clean();

$pdf->stream('relatorio_venda.pdf', array("Attachment" => false));
