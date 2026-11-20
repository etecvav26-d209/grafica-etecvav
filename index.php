<?php

require_once __DIR__ . '/includes/autenticacao.php';

exigirAutenticacao();

$nomeFuncionario = $_SESSION['funcionario_nome'] ?? 'Funcionário';
$perfilFuncionario = $_SESSION['funcionario_perfil'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Gráfica ETEC VAV</title>
</head>
<body>
    <main>
        <h1>Gráfica ETEC Vasco Antônio Venchiarutti</h1>
        <h2>Painel inicial</h2>

        <p>
            Bem-vindo(a),
            <?= htmlspecialchars($nomeFuncionario, ENT_QUOTES, 'UTF-8') ?>.
        </p>

        <p>
            Perfil:
            <?= htmlspecialchars($perfilFuncionario, ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p><a href="logout.php">Sair do sistema</a></p>
    </main>
</body>
</html>
