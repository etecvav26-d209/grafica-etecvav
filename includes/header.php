<?php

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
            <a href="/grafica-etecvav/logout.php">Sair</a>
        </nav>
    <?php endif; ?>

    <main>
