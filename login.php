<?php

require_once __DIR__ . '/includes/autenticacao.php';
require_once __DIR__ . '/config/conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($usuario === '' || $senha === '') {
        $erro = 'Informe o usuário e a senha.';
    } else {
        $sql = 'SELECT id, nome, usuario, senha_hash, perfil
                FROM funcionarios
                WHERE usuario = :usuario AND ativo = 1
                LIMIT 1';

        $consulta = $pdo->prepare($sql);
        $consulta->execute(['usuario' => $usuario]);
        $funcionario = $consulta->fetch();

        if ($funcionario && password_verify($senha, $funcionario['senha_hash'])) {
            session_regenerate_id(true);

            $_SESSION['funcionario_id'] = $funcionario['id'];
            $_SESSION['funcionario_nome'] = $funcionario['nome'];
            $_SESSION['funcionario_usuario'] = $funcionario['usuario'];
            $_SESSION['funcionario_perfil'] = $funcionario['perfil'];

            header('Location: index.php');
            exit;
        }

        $erro = 'Usuário ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gráfica ETEC VAV</title>
</head>
<body>
    <main>
        <h1>Gráfica ETEC Vasco Antônio Venchiarutti</h1>
        <h2>Entrar</h2>

        <?php if ($erro !== ''): ?>
            <p role="alert"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div>
                <label for="usuario">Usuário</label>
                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    required
                    autocomplete="username"
                >
            </div>

            <div>
                <label for="senha">Senha</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit">Entrar</button>
        </form>
    </main>
</body>
</html>
