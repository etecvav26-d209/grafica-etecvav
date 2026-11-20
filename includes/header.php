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
    <main>
