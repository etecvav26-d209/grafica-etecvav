<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('reservas.gerenciar');

require_once __DIR__ . '/../../config/conexao.php';

$mensagemErro = '';
$mensagemSucesso = $_SESSION['mensagem_armario'] ?? '';
unset($_SESSION['mensagem_armario']);

$numero = '';
$tamanho = '';

if (empty($_SESSION['token_armario'])) {
    $_SESSION['token_armario'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroEnviado = $_POST['numero'] ?? '';
    $tamanhoEnviado = $_POST['tamanho'] ?? '';
    $tokenEnviado = $_POST['token_armario'] ?? '';

    if (is_string($numeroEnviado)) {
        $numero = trim($numeroEnviado);
    }

    if (is_string($tamanhoEnviado)) {
        $tamanho = $tamanhoEnviado;
    }

    if (
        !is_string($tokenEnviado)
        || !hash_equals($_SESSION['token_armario'], $tokenEnviado)
    ) {
        $mensagemErro = 'Não foi possível validar o formulário. Tente novamente.';
    } elseif (
        preg_match('/\A.{1,10}\z/u', $numero) !== 1
        || !in_array($tamanho, ['grande', 'pequeno'], true)
    ) {
        $mensagemErro = 'Informe um número e um tamanho válidos.';
    } else {
        try {
            $consultaNumero = $pdo->prepare(
                'SELECT id
                 FROM armario
                 WHERE numero = :numero
                 LIMIT 1'
            );
            $consultaNumero->execute(['numero' => $numero]);

            if ($consultaNumero->fetchColumn()) {
                $mensagemErro = 'Já existe um armário com esse número.';
            } else {
                $inserirArmario = $pdo->prepare(
                    'INSERT INTO armario (numero, tamanho)
                     VALUES (:numero, :tamanho)'
                );
                $inserirArmario->execute([
                    'numero' => $numero,
                    'tamanho' => $tamanho,
                ]);

                $_SESSION['mensagem_armario'] =
                    'Armário cadastrado com sucesso.';
                $_SESSION['token_armario'] = bin2hex(random_bytes(32));

                header('Location: cadastrar_armario.php');
                exit;
            }
        } catch (PDOException $erro) {
            if (
                isset($erro->errorInfo[1])
                && (int) $erro->errorInfo[1] === 1062
            ) {
                $mensagemErro = 'Já existe um armário com esse número.';
            } else {
                $mensagemErro =
                    'Não foi possível cadastrar o armário. Tente novamente.';
            }
        }
    }
}

$tituloPagina = 'Cadastrar armário - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Cadastrar armário</h1>

<?php if ($mensagemSucesso !== ''): ?>
    <p role="status">
        <?= htmlspecialchars($mensagemSucesso, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<?php if ($mensagemErro !== ''): ?>
    <p role="alert">
        <?= htmlspecialchars($mensagemErro, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="post">
    <input
        type="hidden"
        name="token_armario"
        value="<?= htmlspecialchars(
            $_SESSION['token_armario'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
    >

    <div>
        <label for="numero">Número do armário</label>
        <input
            type="text"
            id="numero"
            name="numero"
            maxlength="10"
            required
            value="<?= htmlspecialchars(
                $numero,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >
    </div>

    <div>
        <label for="tamanho">Tamanho</label>
        <select id="tamanho" name="tamanho" required>
            <option value="">Selecione</option>
            <option
                value="grande"
                <?= $tamanho === 'grande' ? 'selected' : '' ?>
            >
                Grande
            </option>
            <option
                value="pequeno"
                <?= $tamanho === 'pequeno' ? 'selected' : '' ?>
            >
                Pequeno
            </option>
        </select>
    </div>

    <button type="submit">Cadastrar armário</button>
</form>

<p>
    <a href="listar.php">Voltar à lista de armários</a>
</p>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>