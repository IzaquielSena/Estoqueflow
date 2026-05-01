<?php
    require_once "../../classes/conexao.php";
    require_once "../../classes/dashboard.php";
    require_once "../../classes/vendas.php";

    $obj = new dashboard();
    $objV = new vendas();

    $vendasHoje = $obj->totalVendasHoje();
    $vendasMes = $obj->totalVendasMes();
    $qtdVendasMes = $obj->quantidadeVendasMes();
    $ticketMedio = $qtdVendasMes > 0 ? $vendasMes / $qtdVendasMes : 0;
    $baixoEstoque = $obj->totalProdutosBaixoEstoque();
?>

<!-- Cards de Resumo -->
<div class="stats-grid">
    <div class="stat-card stat-card-success">
        <div class="stat-card-icon">
            <span class="glyphicon glyphicon-usd"></span>
        </div>
        <div class="stat-card-info">
            <div class="stat-card-value">R$ <?php echo number_format($vendasHoje, 2, ',', '.'); ?></div>
            <div class="stat-card-label">Vendas Hoje</div>
        </div>
    </div>
    <div class="stat-card stat-card-info">
        <div class="stat-card-icon">
            <span class="glyphicon glyphicon-calendar"></span>
        </div>
        <div class="stat-card-info">
            <div class="stat-card-value">R$ <?php echo number_format($vendasMes, 2, ',', '.'); ?></div>
            <div class="stat-card-label">Vendas no Mês</div>
        </div>
    </div>
    <div class="stat-card stat-card-primary">
        <div class="stat-card-icon">
            <span class="glyphicon glyphicon-shopping-cart"></span>
        </div>
        <div class="stat-card-info">
            <div class="stat-card-value">R$ <?php echo number_format($ticketMedio, 2, ',', '.'); ?></div>
            <div class="stat-card-label">Ticket Médio</div>
        </div>
    </div>
    <div class="stat-card <?php echo $baixoEstoque > 0 ? 'stat-card-danger' : 'stat-card-default'; ?>">
        <div class="stat-card-icon">
            <span class="glyphicon glyphicon-exclamation-sign"></span>
        </div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?php echo $baixoEstoque; ?></div>
            <div class="stat-card-label">Itens c/ Baixo Estoque</div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 24px;">
    <!-- Top Produtos -->
    <div class="col-sm-6">
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern"><span class="glyphicon glyphicon-stats"></span> Top 5 Produtos Mais Vendidos</h3>
            </div>
            <div class="card-body-modern" style="padding: 0;">
                <table class="table-modern" style="margin: 0; border-radius: 0;">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th style="text-align: right;">Qtd. Vendida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $topProd = $obj->topProdutosMaisVendidos();
                            while($ver = mysqli_fetch_assoc($topProd)):
                        ?>
                        <tr>
                            <td><?php echo $ver['nome']; ?></td>
                            <td style="text-align: right;"><span class="badge-modern badge-primary-modern"><?php echo $ver['total_vendido']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Faturamento por Categoria -->
    <div class="col-sm-6">
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern"><span class="glyphicon glyphicon-list"></span> Faturamento por Categoria</h3>
            </div>
            <div class="card-body-modern" style="padding: 0;">
                <table class="table-modern" style="margin: 0; border-radius: 0;">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th style="text-align: right;">Faturamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $fatCat = $obj->faturamentoPorCategoria();
                            while($ver = mysqli_fetch_assoc($fatCat)):
                        ?>
                        <tr>
                            <td><?php echo $ver['nome_categoria']; ?></td>
                            <td style="text-align: right;">R$ <?php echo number_format($ver['faturamento'], 2, ',', '.'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 24px;">
    <!-- Últimas Vendas -->
    <div class="col-sm-12">
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern"><span class="glyphicon glyphicon-time"></span> Atividade Recente (Últimas Vendas)</h3>
            </div>
            <div class="card-body-modern" style="padding: 0;">
                <table class="table-modern" style="margin: 0; border-radius: 0;">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $ultVendas = $obj->ultimasVendas();
                            while($ver = mysqli_fetch_assoc($ultVendas)):
                        ?>
                        <tr>
                            <td><span class="badge-modern badge-primary-modern">#<?php echo $ver['id_venda']; ?></span></td>
                            <td><?php echo date("d/m/Y", strtotime($ver['dataCompra'])); ?></td>
                            <td><?php echo $objV->nomeCliente($ver['id_cliente']); ?></td>
                            <td style="text-align: right;"><strong>R$ <?php echo number_format($ver['total'], 2, ',', '.'); ?></strong></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div style="padding: 12px 20px; text-align: right; border-top: 1px solid var(--border-color, #e2e8f0);">
                <a href="vendas.php" class="btn-modern btn-primary-modern btn-sm-modern">Ver Todas as Vendas</a>
            </div>
        </div>
    </div>
</div>
