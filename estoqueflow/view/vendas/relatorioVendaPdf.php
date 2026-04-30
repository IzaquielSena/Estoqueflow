<?php 
	require_once "../../classes/conexao.php";
	require_once "../../classes/vendas.php";

	$objv= new vendas();

	$c= new conectar();
	$conexao=$c->conexao();
	$idvenda=$_GET['idvenda'];

    // Consulta inicial para pegar dados do cabeçalho
    $sql="SELECT ve.id_venda, ve.dataCompra, ve.id_cliente
	        from vendas as ve 
	        where ve.id_venda='$idvenda'";

    $result=mysqli_query($conexao,$sql);
	$ver=mysqli_fetch_row($result);

	$comp=$ver[0];
	$data=$ver[1];
	$idcliente=$ver[2];
 ?>	

 	<link rel="stylesheet" type="text/css" href="../../lib/bootstrap/css/bootstrap.css">
 
 		<img src="../../img/ximac.jpg" width="200" height="120">
 		<br>
 		<table class="table">
 			<tr>
 				<td>Data: <?php echo date("d/m/Y", strtotime($data)) ?></td>
 			</tr>
 			<tr>
 				<td>Numero do Comprovante: <?php echo $comp ?></td>
 			</tr>
 			<tr>
 				<td>Cliente: <?php echo $objv->nomeCliente($idcliente); ?></td>
 			</tr>
 		</table>

 		<table class="table">
 			<tr>
 				<td>Produto: </td>
 				<td>Preço Unit.</td>
 				<td>Quantidade</td>
 				<td>Descrição</td>
 				<td>Subtotal</td>
 			</tr>

 			<?php 
 			// Consulta ajustada para pegar os dados corretos da tabela vendas e produtos
 			$sql="SELECT pro.nome,
				        ve.preco,
				        pro.descricao,
				        ve.quantidade,
				        ve.total_venda
					from vendas as ve 
					inner join produtos as pro
					on ve.id_produto=pro.id_produto
					and ve.id_venda='$idvenda'";

			$result=mysqli_query($conexao,$sql);
			$total=0;
			while($mostrar=mysqli_fetch_row($result)):
 			 ?>

			<tr>
				<td><?php echo $mostrar[0]; ?></td>
				<td><?php echo "R$ " . number_format($mostrar[1], 2, ',', '.'); ?></td>
				<td><?php echo $mostrar[3]; ?></td>
				<td><?php echo $mostrar[2]; ?></td>
				<td><?php echo "R$ " . number_format($mostrar[4], 2, ',', '.'); ?></td>
			</tr>
			<?php 
				// Soma o total acumulando o total_venda de cada item
				$total = $total + $mostrar[4];
			endwhile;
			?>
			<tr>
				<td colspan="5" style="text-align: right; font-weight: bold;">
					Total: <?php echo "R$ " . number_format($total, 2, ',', '.'); ?>
				</td>
			</tr>
 		</table>