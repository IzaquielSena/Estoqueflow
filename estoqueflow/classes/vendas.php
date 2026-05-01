<?php 

class vendas{
    public function obterDadosProduto($idproduto){
        $c= new conectar();
        $conexao=$c->conexao();

        // Busca dados do produto e imagem
        $sql="SELECT pro.nome,
        pro.descricao,
        img.url
        from produtos as pro 
        inner join imagens as img
        on pro.id_imagem=img.id_imagem 
        where pro.id_produto='$idproduto'";
        $result=mysqli_query($conexao,$sql);

        $ver=mysqli_fetch_row($result);

        // Busca quantidade total em estoque
        $sqlQtd="SELECT COALESCE(SUM(quantidade), 0) as total
                 from entradas_estoque 
                 where id_produto='$idproduto'";
        $resultQtd=mysqli_query($conexao,$sqlQtd);
        $rowQtd=mysqli_fetch_assoc($resultQtd);
        $quantidade=$rowQtd['total'];

        // Busca preço atual (última entrada)
        $sqlPreco="SELECT preco
                   from entradas_estoque 
                   where id_produto='$idproduto'
                   ORDER BY data_entrada DESC
                   LIMIT 1";
        $resultPreco=mysqli_query($conexao,$sqlPreco);
        $rowPreco=mysqli_fetch_assoc($resultPreco);
        $preco=$rowPreco ? $rowPreco['preco'] : 0;

        $d=explode('/', $ver[2]);

        $img=$d[1].'/'.$d[2].'/'.$d[3];

        $dados=array(
            'nome' => $ver[0],
            'descricao' => $ver[1],
            'quantidade' => $quantidade,
            'url' => $img,
            'preco' => $preco
        );      
        return $dados;
    }

    public function criarVenda(){
        $c= new conectar();
        $conexao=$c->conexao();

        $data=date('Y-m-d');
        // Obter o ID da venda que será criada (para retorno)
        $idvenda = null;
        $dados=$_SESSION['tabelaComprasTemp'];
        $idusuario=$_SESSION['iduser'];
        $r=0;

        for ($i=0; $i < count($dados) ; $i++) { 
            $d=explode("||", $dados[$i]);

            $idproduto = $d[0];
            $precoUnitario = $d[3];
            $quantidade = $d[6];
            
            // Calcula o total do item garantindo decimais
            $totalItem = $precoUnitario * $quantidade;

            // Remover id_venda da inserção - deixar AUTO_INCREMENT gerar
            $sql="INSERT into vendas (id_cliente,
                                        id_produto,
                                        id_usuario,
                                        preco,
                                        quantidade,
                                        total_venda,
                                        dataCompra)
                            values ('$d[8]',
                                    '$idproduto',
                                    '$idusuario',
                                    '$precoUnitario',
                                    '$quantidade',
                                    '$totalItem',
                                    '$data')";
            
            // Executa a inserção da venda
            $result = mysqli_query($conexao, $sql);

            if($result){
                // Capturar o ID da venda gerado automaticamente na primeira inserção
                if($idvenda === null){
                    $idvenda = mysqli_insert_id($conexao);
                }
                
                // Cria entrada de estoque negativa para registrar a saída
                $quantidadeNegativa = -$quantidade;
                $sqlSaida = "INSERT into entradas_estoque (id_produto, id_usuario, quantidade, preco, data_entrada, dataCaptura)
                            values ('$idproduto', '$idusuario', '$quantidadeNegativa', '$precoUnitario', '$data', '$data')";
                
                if(mysqli_query($conexao, $sqlSaida)){
                    $r++;
                }
            }
        }

        // Limpa o carrinho após finalizar para evitar reenvío de dados
        unset($_SESSION['tabelaComprasTemp']);

        return $r;
    }

    // DEPRECATED: Esta funcao nao eh mais necessaria apos implementar AUTO_INCREMENT
    // Mantida por compatibilidade com codigo legado
    public function criarComprovante(){
        $c= new conectar();
        $conexao=$c->conexao();

        $sql="SELECT MAX(id_venda) as max_id from vendas";

        $resul=mysqli_query($conexao,$sql);
        $row=mysqli_fetch_assoc($resul);
        $id=$row['max_id'];

        if($id=="" or $id==null or $id==0){
            return 1;
        }else{
            return $id + 1;
        }
    }

    public function nomeCliente($idCliente){
        $c = new conectar();
        $conexao = $c->conexao();

        if(empty($idCliente) || $idCliente == 0 || $idCliente == "Sem Cliente"){
            return "Sem Cliente";
        }

        $sql = "SELECT sobrenome, nome 
                from clientes 
                where id_cliente='$idCliente'";
        $result = mysqli_query($conexao, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $ver = mysqli_fetch_row($result);
            return $ver[1] . " " . $ver[0];
        } else {
            return "Sem Cliente"; 
        }
    }

    public function obterTotal($idvenda){
        $c= new conectar();
        $conexao=$c->conexao();

        $sql="SELECT total_venda 
                from vendas 
                where id_venda='$idvenda'";
        $result=mysqli_query($conexao,$sql);

        $total=0;
        while($ver=mysqli_fetch_row($result)){
            $total = $total + $ver[0];
        }
        return $total;
    }

    // No arquivo classes/vendas.php
public function obterTodasVendasGeral(){
    $c = new conectar();
    $conexao = $c->conexao();

    // Seleciona todas as vendas agrupadas por ID para não repetir itens da mesma venda
    $sql = "SELECT id_venda, dataCompra, id_cliente 
            FROM vendas 
            GROUP BY id_venda 
            ORDER BY dataCompra DESC";
    $result = mysqli_query($conexao, $sql);

    return $result;
}

    public function relatorioVendasPorData($dataInicio, $dataFim){
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT id_venda, dataCompra, id_cliente 
                FROM vendas 
                WHERE dataCompra BETWEEN '$dataInicio' AND '$dataFim' 
                GROUP BY id_venda";
        return mysqli_query($conexao, $sql);
    }
}
?>
