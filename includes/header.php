<?php
require_once __DIR__ . '/permissoes.php';
$tituloPagina = $tituloPagina ?? 'Gráfica ETEC VAV';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/grafica-etecvav/css/style.css">
</head>
<body>
    <?php if (isset($_SESSION['funcionario_id'])): ?>
        <nav class="navegacao" aria-label="Navegação principal">
    <a href="/grafica-etecvav/index.php">Início</a>

    <?php if (funcionarioPode('funcionarios.gerenciar')): ?>
        <a href="/grafica-etecvav/pages/funcionarios/listar.php">
            Funcionários
        </a>
    <?php endif; ?>

    <?php if (funcionarioPode('pedidos.consultar')): ?>
        <a href="/grafica-etecvav/pages/pedidos/listar.php">
            Pedidos
        </a>
    <?php endif; ?>

    <?php if (funcionarioPode('vendas.consultar') || funcionarioPode('vendas.registrar')): ?>
        <a href="/grafica-etecvav/pages/vendas/listar.php">
            Vendas
        </a>
    <?php endif; ?>

    <?php if (funcionarioPode('produtos.consultar')): ?>
        <a href="/grafica-etecvav/pages/produtos/listar.php">
            Produtos
        </a>
    <?php endif; ?>

    <?php if (funcionarioPode('estoque.consultar') || funcionarioPode('estoque.gerenciar')): ?>
        <a href="/grafica-etecvav/pages/estoque/consultar.php">
            Estoque
        </a>
    <?php endif; ?>

    <?php if (funcionarioPode('reservas.consultar') || funcionarioPode('reservas.solicitar')): ?>
        <a href="/grafica-etecvav/pages/armarios/reservas.php">
            Reservas
        </a>
    <?php endif; ?>

    <?php if (funcionarioPode('manutencoes.consultar') || funcionarioPode('manutencoes.registrar')): ?>
        <a href="/grafica-etecvav/pages/manutencoes/listar.php">
            Manutenções
        </a>
    <?php endif; ?>

    <?php if (funcionarioPode('relatorios.consultar')): ?>
        <a href="/grafica-etecvav/pages/relatorios/listar.php">
            Relatórios
        </a>
    <?php endif; ?>

    <a href="/grafica-etecvav/logout.php">Sair</a>
</nav>
    <?php endif; ?>

    <main>
