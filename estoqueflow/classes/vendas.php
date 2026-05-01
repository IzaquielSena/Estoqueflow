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

        // Busca preco de venda (última entrada)
        $sqlPreco="SELECT preco_venda
                   from entradas_estoque 
                   where id_produto='$idproduto' AND preco_venda > 0
                   ORDER BY data_entrada DESC
                   LIMIT 1";
        $resultPreco=mysqli_query($conexao,$sqlPreco);
        $rowPreco=mysqli_fetch_assoc($resultPreco);
        $preco_venda = $rowPreco ? $rowPreco['preco_venda'] : 0;

        // Se não tiver preco_venda, busca preco de custo como fallback
        if($preco_venda == 0){
            $sqlPrecoCusto="SELECT preco
                       from entradas_estoque 
                       where id_produto='$idproduto'
                       ORDER BY data_entrada DESC
                       LIMIT 1";
            $resultPrecoCusto=mysqli_query($conexao,$sqlPrecoCusto);
            $rowPrecoCusto=mysqli_fetch_assoc($resultPrecoCusto);
            $preco_venda=$rowPrecoCusto ? $rowPrecoCusto['preco'] : 0;
        }

        // Busca preco de custo (última entrada)
        $sqlCusto="SELECT preco
                   from entradas_estoque 
                   where id_produto='$idproduto'
                   ORDER BY data_entrada DESC
                   LIMIT 1";
        $resultCusto=mysqli_query($conexao,$sqlCusto);
        $rowCusto=mysqli_fetch_assoc($resultCusto);
        $preco_custo=$rowCusto ? $rowCusto['preco'] : 0;

        $d=explode('/', $ver[2]);

        $img=$d[1].'/'.$d[2].'/'.$d[3];

        $dados=array(
            'nome' => $ver[0],
            'descricao' => $ver[1],
            'quantidade' => $quantidade,
            'url' => $img,
            'preco' => $preco_venda,
            'preco_custo' => $preco_custo
        );      
        return $dados;
    }

    public function criarVenda(){
        $c= new conectar();
        $conexao=$c->conexao();

        $data=date('Y-m-d');
        
        // Gerar codigo_venda unico para agrupar todos os itens desta venda
        $sqlCodigo = "SELECT COALESCE(MAX(codigo_venda), 0) + 1 as proximo FROM vendas";
        $resultCodigo = mysqli_query($conexao, $sqlCodigo);
        $rowCodigo = mysqli_fetch_assoc($resultCodigo);
        $codigoVenda = $rowCodigo['proximo'];

        $dados=$_SESSION['tabelaComprasTemp'];
        $idusuario=$_SESSION['iduser'];
        $r=0;

        for ($i=0; $i < count($dados) ; $i++) { 
            $d=explode("||", $dados[$i]);

            $idproduto = $d[0];
            $precoVenda = $d[3];
            $precoCusto = isset($d[9]) ? $d[9] : 0;
            $quantidade = $d[6];
            
            $totalItem = $precoVenda * $quantidade;

            $sql="INSERT into vendas (codigo_venda,
                                        id_cliente,
                                        id_produto,
                                        id_usuario,
                                        preco,
                                        preco_custo,
                                        quantidade,
                                        total_venda,
                                        dataCompra)
                            values ('$codigoVenda',
                                    '$d[8]',
                                    '$idproduto',
                                    '$idusuario',
                                    '$precoVenda',
                                    '$precoCusto',
                                    '$quantidade',
                                    '$totalItem',
                                    '$data')";
            
            $result = mysqli_query($conexao, $sql);

            if($result){
                // Cria entrada de estoque negativa para registrar a saída
                $quantidadeNegativa = -$quantidade;
                $sqlSaida = "INSERT into entradas_estoque (id_produto, id_usuario, quantidade, preco, preco_venda, data_entrada, dataCaptura)
                            values ('$idproduto', '$idusuario', '$quantidadeNegativa', '$precoCusto', '$precoVenda', '$data', '$data')";
                
                if(mysqli_query($conexao, $sqlSaida)){
                    $r++;
                }
            }
        }

        // Limpa o carrinho após finalizar
        unset($_SESSION['tabelaComprasTemp']);

        return $codigoVenda;
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

    public function obterTotal($codigoVenda){
        $c= new conectar();
        $conexao=$c->conexao();

        $sql="SELECT total_venda 
                from vendas 
                where codigo_venda='$codigoVenda'";
        $result=mysqli_query($conexao,$sql);

        $total=0;
        while($ver=mysqli_fetch_row($result)){
            $total = $total + $ver[0];
        }
        return $total;
    }

    public function obterLucro($codigoVenda){
        $c= new conectar();
        $conexao=$c->conexao();

        $sql="SELECT preco, preco_custo, quantidade 
                from vendas 
                where codigo_venda='$codigoVenda'";
        $result=mysqli_query($conexao,$sql);

        $lucro=0;
        while($ver=mysqli_fetch_row($result)){
            $precoVenda = $ver[0];
            $precoCusto = $ver[1] ? $ver[1] : 0;
            $quantidade = $ver[2];
            $lucro += ($precoVenda - $precoCusto) * $quantidade;
        }
        return $lucro;
    }

    public function obterTodasVendasGeral(){
        $c = new conectar();
        $conexao = $c->conexao();

        $sql = "SELECT codigo_venda, dataCompra, id_cliente 
                FROM vendas 
                GROUP BY codigo_venda 
                ORDER BY codigo_venda DESC";
        $result = mysqli_query($conexao, $sql);

        return $result;
    }

    public function relatorioVendasPorData($dataInicio, $dataFim){
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT codigo_venda, dataCompra, id_cliente 
                FROM vendas 
                WHERE dataCompra BETWEEN '$dataInicio' AND '$dataFim' 
                GROUP BY codigo_venda";
        return mysqli_query($conexao, $sql);
    }
}
?>
