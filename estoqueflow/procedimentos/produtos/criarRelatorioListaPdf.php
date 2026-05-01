<?php
	session_start();
	require_once "../../classes/conexao.php";
	require_once "../../classes/entradas_estoque.php";

	$c = new conectar();
	$conexao = $c->conexao();
	$obj_entrada = new entradas_estoque();

	// Importar FPDF
	require_once "../../vendor/autoload.php";
	use FPDF\FPDF;

	// Criar PDF
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(0, 10, 'Lista de Produtos em Estoque', 0, 1, 'C');
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(0, 5, 'Data: ' . date('d/m/Y H:i:s'), 0, 1, 'C');
	$pdf->Ln(5);

	// Cabeçalho da tabela
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetFillColor(0, 102, 204);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->Cell(40, 7, 'Produto', 1, 0, 'L', true);
	$pdf->Cell(30, 7, 'Categoria', 1, 0, 'L', true);
	$pdf->Cell(25, 7, 'Qtd.', 1, 0, 'C', true);
	$pdf->Cell(30, 7, 'Preco Unit.', 1, 0, 'R', true);
	$pdf->Cell(35, 7, 'Valor Total', 1, 1, 'R', true);

	// Dados da tabela
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetTextColor(0, 0, 0);

	$sql = "SELECT DISTINCT p.id_produto, 
				p.nome, 
				c.nome_categoria
			FROM produtos p
			INNER JOIN categorias c ON p.id_categoria = c.id_categoria
			ORDER BY p.nome";

	$result = mysqli_query($conexao, $sql);
	$valor_total_estoque = 0;

	while($mostrar = mysqli_fetch_assoc($result)){
		$quantidade = $obj_entrada->obterQuantidadeTotal($mostrar['id_produto']);
		$preco = $obj_entrada->obterPrecoAtual($mostrar['id_produto']);
		$valor_total = $quantidade * $preco;
		$valor_total_estoque += $valor_total;

		// Truncar nome se muito longo
		$nome = substr($mostrar['nome'], 0, 20);
		$categoria = substr($mostrar['nome_categoria'], 0, 15);

		$pdf->Cell(40, 6, $nome, 1, 0, 'L');
		$pdf->Cell(30, 6, $categoria, 1, 0, 'L');
		$pdf->Cell(25, 6, $quantidade, 1, 0, 'C');
		$pdf->Cell(30, 6, 'R$ ' . number_format($preco, 2, ',', '.'), 1, 0, 'R');
		$pdf->Cell(35, 6, 'R$ ' . number_format($valor_total, 2, ',', '.'), 1, 1, 'R');
	}

	// Total
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetFillColor(200, 200, 200);
	$pdf->Cell(125, 7, 'TOTAL DO ESTOQUE:', 1, 0, 'R', true);
	$pdf->Cell(35, 7, 'R$ ' . number_format($valor_total_estoque, 2, ',', '.'), 1, 1, 'R', true);

	// Rodapé
	$pdf->Ln(10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetTextColor(128, 128, 128);
	$pdf->Cell(0, 5, 'Relatório gerado em ' . date('d/m/Y H:i:s') . ' por ' . $_SESSION['usuario'], 0, 1, 'C');

	// Saída do PDF
	$pdf->Output('D', 'Lista_Produtos_' . date('d-m-Y_H-i-s') . '.pdf');
?>
