<?php
class dashboard {
    public function totalVendasHoje() {
        $c = new conectar();
        $conexao = $c->conexao();
        $hoje = date('Y-m-d');
        $sql = "SELECT SUM(total_venda) as total FROM vendas WHERE dataCompra = '$hoje'";
        $result = mysqli_query($conexao, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    public function totalVendasMes() {
        $c = new conectar();
        $conexao = $c->conexao();
        $mes = date('m');
        $ano = date('Y');
        $sql = "SELECT SUM(total_venda) as total FROM vendas WHERE MONTH(dataCompra) = '$mes' AND YEAR(dataCompra) = '$ano'";
        $result = mysqli_query($conexao, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    public function quantidadeVendasMes() {
        $c = new conectar();
        $conexao = $c->conexao();
        $mes = date('m');
        $ano = date('Y');
        $sql = "SELECT COUNT(DISTINCT id_venda) as total FROM vendas WHERE MONTH(dataCompra) = '$mes' AND YEAR(dataCompra) = '$ano'";
        $result = mysqli_query($conexao, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    public function produtosBaixoEstoque($limite = 5) {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT p.nome, COALESCE(SUM(e.quantidade), 0) as estoque 
                FROM produtos p 
                LEFT JOIN entradas_estoque e ON p.id_produto = e.id_produto 
                GROUP BY p.id_produto 
                HAVING estoque <= $limite 
                ORDER BY estoque ASC";
        return mysqli_query($conexao, $sql);
    }

    public function totalProdutosBaixoEstoque($limite = 5) {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT COUNT(*) as total FROM (
                    SELECT p.id_produto, COALESCE(SUM(e.quantidade), 0) as estoque 
                    FROM produtos p 
                    LEFT JOIN entradas_estoque e ON p.id_produto = e.id_produto 
                    GROUP BY p.id_produto 
                    HAVING estoque <= $limite
                ) as sub";
        $result = mysqli_query($conexao, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    public function topProdutosMaisVendidos($limite = 5) {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT p.nome, SUM(v.quantidade) as total_vendido 
                FROM vendas v 
                JOIN produtos p ON v.id_produto = p.id_produto 
                GROUP BY v.id_produto 
                ORDER BY total_vendido DESC 
                LIMIT $limite";
        return mysqli_query($conexao, $sql);
    }

    public function faturamentoPorCategoria() {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT c.nome_categoria, SUM(v.total_venda) as faturamento 
                FROM vendas v 
                JOIN produtos p ON v.id_produto = p.id_produto 
                JOIN categorias c ON p.id_categoria = c.id_categoria 
                GROUP BY c.id_categoria 
                ORDER BY faturamento DESC";
        return mysqli_query($conexao, $sql);
    }

    public function ultimasVendas($limite = 5) {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT id_venda, dataCompra, id_cliente, SUM(total_venda) as total 
                FROM vendas 
                GROUP BY id_venda 
                ORDER BY id_venda DESC 
                LIMIT $limite";
        return mysqli_query($conexao, $sql);
    }
}
?>
