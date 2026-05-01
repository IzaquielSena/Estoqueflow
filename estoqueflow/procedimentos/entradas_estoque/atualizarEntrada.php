<?php 
	require_once "../../classes/conexao.php";
	require_once "../../classes/entradas_estoque.php";

	$obj = new entradas_estoque();

	if(isset($_POST['idEntrada']) && isset($_POST['quantidadeU']) && isset($_POST['precoU']) && isset($_POST['dataEntradaU'])){
		$preco = str_replace(",", ".", $_POST['precoU']); // Converter vírgula para ponto
		
		$dados = array(
			$_POST['idEntrada'],
			$_POST['quantidadeU'],
			$preco,
			$_POST['dataEntradaU']
		);

		echo $obj->atualizar($dados);
	}else{
		echo 0;
	}
?>
