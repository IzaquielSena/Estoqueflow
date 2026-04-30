<?php 
session_start();
if(isset($_SESSION['usuario'])){
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>EstoqueFlow - Lista de Produtos</title>
	<?php require_once "menu.php"; ?>
</head>
<body>

<div class="content-area fade-in">
	<div class="page-header">
		<h1>Lista de Produtos</h1>
		<p>Visualize todos os produtos cadastrados, quantidade em estoque e valor atual.</p>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="card-modern">
				<div class="card-header-modern" style="display:flex; justify-content:space-between; align-items:center;">
					<h4><span class="glyphicon glyphicon-list"></span> Produtos em Estoque</h4>
					<form id="frmRelatorioListaPdf" action="../procedimentos/produtos/criarRelatorioListaPdf.php" method="POST" target="_blank" style="display: inline;">
						<button class="btn-modern btn-success-modern btn-sm-modern">
							<span class="glyphicon glyphicon-file"></span> Gerar Relatório PDF
						</button>
					</form>
				</div>
				<div class="card-body-modern">
					<div id="tabelaProdutosLoad"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="app-footer">
	EstoqueFlow &copy; <?php echo date('Y'); ?> - Sistema de Controle de Estoque
</div>

</div><!-- Close main-content -->

</body>
</html>

<script type="text/javascript">
	$(document).ready(function(){
		$('#tabelaProdutosLoad').load("produtos/tabelaListaProdutos.php");

		setInterval(function(){
			$('#tabelaProdutosLoad').load("produtos/tabelaListaProdutos.php");
		}, 30000);
	});
</script>

<?php 
}else{
	header("location:../index.php");
}
?>
