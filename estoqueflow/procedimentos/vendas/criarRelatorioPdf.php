<?php
ob_start(); // Previne que avisos do PHP quebrem o PDF

require_once '../../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$id = isset($_GET['idvenda']) ? $_GET['idvenda'] : null;

if (!$id) {
    die("ID da venda não fornecido.");
}

// Função cURL para capturar o relatório
function buscar_relatorio($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $dados = curl_exec($ch);
    curl_close($ch);
    return $dados;
}

$url = "http://localhost/estoqueflow/view/vendas/relatorioVendaPdf.php?idvenda=" . $id;
$html = buscar_relatorio($url);

if (!$html) {
    ob_end_clean();
    die("Erro ao capturar o HTML do relatório.");
}

$pdf = new Dompdf();
// Papel tamanho carta na vertical
$pdf->setPaper("letter", "portrait"); 
$pdf->loadHtml($html);
$pdf->render();

ob_end_clean(); // Limpa saídas acidentais

// Abre em nova aba para visualização
$pdf->stream('relatorio_venda.pdf', array("Attachment" => false));