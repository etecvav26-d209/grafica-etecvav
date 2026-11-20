<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('funcionarios.gerenciar');

require_once __DIR__ . '/../../config/conexao.php';

$idRecebido = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? ($_POST['id'] ?? null)
    : ($_GET['id'] ?? null);

$id = filter_var(
    $idRecebido,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if ($id === false) {
    http_response_code(400);
    exit('Identificador inválido.');
}

$consulta = $pdo->prepare(
    'SELECT id, nome, usuario, perfil
     FROM funcionarios
     WHERE id = :id'
);
$consulta->execute(['id' => $id]);
$funcionario = $consulta->fetch();

if (!$funcionario) {
    http_response_code(404);
    exit('Funcionário não encontrado.');
}

$erro = '';
$nome = $funcionario['nome'];
$usuario = $funcionario['usuario'];
$perfil = $funcionario['perfil'];

$perfisValidos = [
    'administrador',
    'gerente',
    'atendente',
    'operador',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeRecebido = $_POST['nome'] ?? '';
    $usuarioRecebido = $_POST['usuario'] ?? '';
    $perfilRecebido = $_POST['perfil'] ?? '';

    $nome = is_string($nomeRecebido) ? trim($nomeRecebido) : '';
    $usuario = is_string($usuarioRecebido) ? trim($usuarioRecebido) : '';
    $perfil = is_string($perfilRecebido) ? $perfilRecebido : '';

    if ($nome === '' || $usuario === '' || $perfil === '') {
        $erro = 'Preencha todos os campos.';
    } elseif (
        preg_match('/\A.{1,100}\z/us', $nome) !== 1
        || preg_match('/\A.{1,50}\z/us', $usuario) !== 1
    ) {
        $erro = 'Confira o tamanho do nome e do usuário.';
    } elseif (!in_array($perfil, $perfisValidos, true)) {
        $erro = 'Selecione um perfil válido.';
    } else {
        $sql = 'UPDATE funcionarios
                SET nome = :nome,
                    usuario = :usuario,
                    perfil = :perfil
                WHERE id = :id';

        try {
            $consulta = $pdo->prepare($sql);
            $consulta->execute([
                'nome' => $nome,
                'usuario' => $usuario,
                'perfil' => $perfil,
                'id' => $id,
            ]);

            header('Location: listar.php');
            exit;
        } catch (PDOException $erroBanco) {
            if ($erroBanco->getCode() === '23000') {
                $erro = 'Esse nome de usuário já está cadastrado.';
            } else {
                $erro = 'Não foi possível atualizar o funcionário.';
            }
        }
    }
}

$tituloPagina = 'Editar funcionário - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Editar funcionário</h1>

<?php if ($erro !== ''): ?>
    <p role="alert">
        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="post" action="editar.php">
    <input type="hidden" name="id" value="<?= (int) $id ?>">

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
        <label for="perfil">Nível de acesso</label>
        <select id="perfil" name="perfil" required>
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

    <button type="submit">Salvar alterações</button>
</form>

<p><a href="listar.php">Voltar à listagem</a></p>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>