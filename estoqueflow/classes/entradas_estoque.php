<?php 
	class entradas_estoque{
		
		// Inserir entrada de estoque
		public function inserirEntrada($dados){
			$c= new conectar();
			$conexao=$c->conexao();
			$data=date('Y-m-d');

			// $dados[0] = id_produto
			// $dados[1] = id_usuario
			// $dados[2] = quantidade
			// $dados[3] = preco
			// $dados[4] = data_entrada

			$sql="INSERT into entradas_estoque (id_produto, id_usuario, quantidade, preco, data_entrada, dataCaptura) 
				  values ('$dados[0]', '$dados[1]', '$dados[2]', '$dados[3]', '$dados[4]', '$data')";
			
			return mysqli_query($conexao,$sql);
		}

		// Obter todas as entradas de um produto
		public function obterEntradasProduto($idProduto){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="SELECT id_entrada, 
						id_produto, 
						quantidade,
						preco,
						data_entrada,
						dataCaptura
				from entradas_estoque 
				where id_produto='$idProduto'
				ORDER BY data_entrada DESC";
			
			return mysqli_query($conexao,$sql);
		}

		// Obter dados de uma entrada específica
		public function obterDados($idEntrada){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="SELECT id_entrada, 
						id_produto, 
						quantidade,
						preco,
						data_entrada
				from entradas_estoque 
				where id_entrada='$idEntrada'";
			$result=mysqli_query($conexao,$sql);

			$mostrar=mysqli_fetch_row($result);

			$dados=array(
					"id_entrada" => $mostrar[0],
					"id_produto" => $mostrar[1],
					"quantidade" => $mostrar[2],
					"preco" => $mostrar[3],
					"data_entrada" => $mostrar[4]
						);

			return $dados;
		}

		// Atualizar entrada de estoque
		public function atualizar($dados){
			$c= new conectar();
			$conexao=$c->conexao();

			// $dados[0] = id_entrada
			// $dados[1] = quantidade
			// $dados[2] = preco
			// $dados[3] = data_entrada

			$sql="UPDATE entradas_estoque set quantidade='$dados[1]', 
										preco='$dados[2]',
										data_entrada='$dados[3]'
						where id_entrada='$dados[0]'";

			return mysqli_query($conexao,$sql);
		}

		// Excluir entrada de estoque
		public function excluir($idEntrada){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="DELETE from entradas_estoque 
					where id_entrada='$idEntrada'";
			
			return mysqli_query($conexao,$sql);
		}

		// Obter quantidade total em estoque de um produto
		public function obterQuantidadeTotal($idProduto){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="SELECT COALESCE(SUM(quantidade), 0) as total
					from entradas_estoque 
					where id_produto='$idProduto'";
			
			$result=mysqli_query($conexao,$sql);
			$row=mysqli_fetch_assoc($result);

			return $row['total'];
		}

		// Obter preço atual (última entrada)
		public function obterPrecoAtual($idProduto){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="SELECT preco
					from entradas_estoque 
					where id_produto='$idProduto'
					ORDER BY data_entrada DESC
					LIMIT 1";
			
			$result=mysqli_query($conexao,$sql);
			$row=mysqli_fetch_assoc($result);

			return $row ? $row['preco'] : 0;
		}

		// Listar todas as entradas de estoque com informações do produto
		public function listarTodasEntradas(){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="SELECT e.id_entrada,
						e.id_produto,
						p.nome,
						e.quantidade,
						e.preco,
						e.data_entrada,
						e.dataCaptura
					from entradas_estoque e
					INNER JOIN produtos p ON e.id_produto = p.id_produto
					ORDER BY e.data_entrada DESC";
			
			return mysqli_query($conexao,$sql);
		}
	}

 ?>
