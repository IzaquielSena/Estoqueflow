<?php
ob_start(); // Previne que erros de texto corrompam o PDF

require_once '../../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$id = isset($_GET['idvenda']) ? $_GET['idvenda'] : null;

if (!$id) {
    die("ID da venda não fornecido.");
}

// Função para buscar o HTML via cURL (mais estável em localhost)
function buscar_conteudo($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $dados = curl_exec($ch);
    curl_close($ch);
    return $dados;
}

$url = "http://localhost/estoqueflow/view/vendas/comprovanteVendaPdf.php?idvenda=" . $id;
$html = buscar_conteudo($url);

if (!$html) {
    ob_end_clean();
    die("Erro ao capturar o HTML do comprovante.");
}

$pdf = new Dompdf();
// Papel personalizado para comprovante pequeno
$pdf->setPaper(array(0, 0, 125, 250)); 
$pdf->loadHtml($html);
$pdf->render();

ob_end_clean(); // Limpa o buffer antes de enviar o arquivo

// "Attachment" => false faz abrir no navegador/Adobe em vez de baixar
$pdf->stream('comprovante_venda.pdf', array("Attachment" => false));