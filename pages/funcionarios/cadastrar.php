<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('funcionarios.gerenciar');

require_once __DIR__ . '/../../config/conexao.php';

$erro = '';
$nome = '';
$usuario = '';
$perfil = '';

$perfisValidos = [
    'administrador',
    'gerente',
    'atendente',
    'operador',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeRecebido = $_POST['nome'] ?? '';
    $usuarioRecebido = $_POST['usuario'] ?? '';
    $senhaRecebida = $_POST['senha'] ?? '';
    $perfilRecebido = $_POST['perfil'] ?? '';

    $nome = is_string($nomeRecebido) ? trim($nomeRecebido) : '';
    $usuario = is_string($usuarioRecebido) ? trim($usuarioRecebido) : '';
    $senha = is_string($senhaRecebida) ? $senhaRecebida : '';
    $perfil = is_string($perfilRecebido) ? $perfilRecebido : '';

    if ($nome === '' || $usuario === '' || $senha === '' || $perfil === '') {
        $erro = 'Preencha todos os campos.';
    } elseif (
        preg_match('/\A.{1,100}\z/us', $nome) !== 1
        || preg_match('/\A.{1,50}\z/us', $usuario) !== 1
    ) {
        $erro = 'Confira o tamanho do nome e do usuário.';
    } elseif (!in_array($perfil, $perfisValidos, true)) {
        $erro = 'Selecione um perfil válido.';
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO funcionarios
                    (nome, usuario, senha_hash, perfil, ativo)
                VALUES
                    (:nome, :usuario, :senha_hash, :perfil, 1)';

        try {
            $consulta = $pdo->prepare($sql);
            $consulta->execute([
                'nome' => $nome,
                'usuario' => $usuario,
                'senha_hash' => $senhaHash,
                'perfil' => $perfil,
            ]);

            header('Location: listar.php');
            exit;
        } catch (PDOException $erroBanco) {
            if ($erroBanco->getCode() === '23000') {
                $erro = 'Esse nome de usuário já está cadastrado.';
            } else {
                $erro = 'Não foi possível cadastrar o funcionário.';
            }
        }
    }
}

$tituloPagina = 'Cadastrar funcionário - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Cadastrar funcionário</h1>

<?php if ($erro !== ''): ?>
    <p role="alert">
        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="post" action="cadastrar.php">
    <div>
        <label for="nome">Nome</label>
        <input
            type="text"
            id="nome"
            name="nome"
            maxlength="100"
            value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>"
            required
        >
    </div>

    <div>
        <label for="usuario">Usuário</label>
        <input
            type="text"
            id="usuario"
            name="usuario"
            maxlength="50"
            value="<?= htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') ?>"
            required
            autocomplete="username"
        >
    </div>

    <div>
        <label for="senha">Senha inicial</label>
        <input
            type="password"
            id="senha"
            name="senha"
            required
            autocomplete="new-password"
        >
    </div>

    <div>
        <label for="perfil">Nível de acesso</label>
        <select id="perfil" name="perfil" required>
            <option value="">Selecione</option>
            <option value="administrador" <?= $perfil === 'administrador' ? 'selected' : '' ?>>
                Administrador
            </option>
            <option value="gerente" <?= $perfil === 'gerente' ? 'selected' : '' ?>>
                Gerente
            </option>
            <option value="atendente" <?= $perfil === 'atendente' ? 'selected' : '' ?>>
                Atendente
            </option>
            <option value="operador" <?= $perfil === 'operador' ? 'selected' : '' ?>>
                Operador
            </option>
        </select>
    </div>

    <button type="submit">Cadastrar funcionário</button>
</form>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>