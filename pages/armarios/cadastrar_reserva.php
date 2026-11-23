<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('reservas.solicitar');

require_once __DIR__ . '/../../config/conexao.php';

$mensagemErro = '';
$mensagemSucesso = $_SESSION['mensagem_reserva'] ?? '';
unset($_SESSION['mensagem_reserva']);

$dados = [
    'estudante' => '',
    'sala' => '',
    'curso' => '',
    'armario_id' => '',
    'ano_letivo' => '',
    'data_reserva' => '',
];

if (empty($_SESSION['token_reserva'])) {
    $_SESSION['token_reserva'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dados as $campo => $valorAtual) {
        if (isset($_POST[$campo]) && is_string($_POST[$campo])) {
            $dados[$campo] = trim($_POST[$campo]);
        }
    }

    $tokenEnviado = $_POST['token_reserva'] ?? '';

    if (
        !is_string($tokenEnviado)
        || !hash_equals($_SESSION['token_reserva'], $tokenEnviado)
    ) {
        $mensagemErro = 'Não foi possível validar o formulário. Tente novamente.';
    } else {
        $estudante = $dados['estudante'];
        $sala = $dados['sala'];
        $curso = $dados['curso'];

        $armarioId = filter_var(
            $dados['armario_id'],
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        $anoLetivo = filter_var(
            $dados['ano_letivo'],
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1901, 'max_range' => 2155]]
        );

        $dataReserva = $dados['data_reserva'];

        $estudanteValido = preg_match(
            '/\A.{1,100}\z/u',
            $estudante
        ) === 1;

        $salaValida = preg_match(
            '/\A.{1,30}\z/u',
            $sala
        ) === 1;

        $cursoValido = preg_match(
            '/\A.{1,100}\z/u',
            $curso
        ) === 1;

        $dataValida =
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataReserva) === 1
            && checkdate(
                (int) substr($dataReserva, 5, 2),
                (int) substr($dataReserva, 8, 2),
                (int) substr($dataReserva, 0, 4)
            );

        if (
            !$estudanteValido
            || !$salaValida
            || !$cursoValido
            || $armarioId === false
            || $anoLetivo === false
            || !$dataValida
        ) {
            $mensagemErro = 'Preencha os campos com valores válidos.';
        } else {
            try {
                $pdo->beginTransaction();

                $consultaArmario = $pdo->prepare(
                    'SELECT id
                     FROM armario
                     WHERE id = :id
                     FOR UPDATE'
                );
                $consultaArmario->execute(['id' => $armarioId]);

                if (!$consultaArmario->fetchColumn()) {
                    $pdo->rollBack();
                    $mensagemErro = 'O armário selecionado não existe.';
                } else {
                    $consultaReserva = $pdo->prepare(
                        'SELECT id
                         FROM reservas_armario
                         WHERE armario_id = :armario_id
                           AND ano_letivo = :ano_letivo
                         LIMIT 1'
                    );
                    $consultaReserva->execute([
                        'armario_id' => $armarioId,
                        'ano_letivo' => $anoLetivo,
                    ]);

                    if ($consultaReserva->fetchColumn()) {
                        $pdo->rollBack();
                        $mensagemErro =
                            'Este armário já possui reserva para esse ano letivo.';
                    } else {
                        $inserirReserva = $pdo->prepare(
                            'INSERT INTO reservas_armario (
                                estudante,
                                sala,
                                curso,
                                armario_id,
                                ano_letivo,
                                data_reserva,
                                situacao_pgmento,
                                funcionario_id
                            ) VALUES (
                                :estudante,
                                :sala,
                                :curso,
                                :armario_id,
                                :ano_letivo,
                                :data_reserva,
                                :situacao_pgmento,
                                :funcionario_id
                            )'
                        );

                        $inserirReserva->execute([
                            'estudante' => $estudante,
                            'sala' => $sala,
                            'curso' => $curso,
                            'armario_id' => $armarioId,
                            'ano_letivo' => $anoLetivo,
                            'data_reserva' => $dataReserva,
                            'situacao_pgmento' => 'pendente',
                            'funcionario_id' =>
                                (int) $_SESSION['funcionario_id'],
                        ]);

                        $pdo->commit();

                        $_SESSION['mensagem_reserva'] =
                            'Reserva cadastrada com pagamento pendente.';
                        $_SESSION['token_reserva'] = bin2hex(random_bytes(32));

                        header('Location: cadastrar_reserva.php');
                        exit;
                    }
                }
            } catch (PDOException $erro) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                $mensagemErro =
                    'Não foi possível registrar a reserva. Tente novamente.';
            }
        }
    }
}

$sqlArmarios = 'SELECT id, numero, tamanho
                FROM armario
                ORDER BY numero ASC';

$armarios = $pdo->query($sqlArmarios)->fetchAll();

$tituloPagina = 'Solicitar reserva - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Solicitar reserva de armário</h1>

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

<?php if (count($armarios) === 0): ?>
    <p>Não há armários cadastrados.</p>
<?php else: ?>
    <form method="post">
        <input
            type="hidden"
            name="token_reserva"
            value="<?= htmlspecialchars(
                $_SESSION['token_reserva'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <div>
            <label for="estudante">Nome do estudante</label>
            <input
                type="text"
                id="estudante"
                name="estudante"
                maxlength="100"
                required
                value="<?= htmlspecialchars(
                    $dados['estudante'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
        </div>

        <div>
            <label for="sala">Sala</label>
            <input
                type="text"
                id="sala"
                name="sala"
                maxlength="30"
                required
                value="<?= htmlspecialchars(
                    $dados['sala'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
        </div>

        <div>
            <label for="curso">Curso</label>
            <input
                type="text"
                id="curso"
                name="curso"
                maxlength="100"
                required
                value="<?= htmlspecialchars(
                    $dados['curso'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
        </div>

        <div>
            <label for="armario_id">Armário</label>
            <select id="armario_id" name="armario_id" required>
                <option value="">Selecione</option>
                <?php foreach ($armarios as $armario): ?>
                    <option
                        value="<?= (int) $armario['id'] ?>"
                        <?= $dados['armario_id'] === (string) $armario['id']
                            ? 'selected'
                            : '' ?>
                    >
                        <?= htmlspecialchars(
                            $armario['numero'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                        (<?= htmlspecialchars(
                            $armario['tamanho'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="ano_letivo">Ano letivo</label>
            <input
                type="number"
                id="ano_letivo"
                name="ano_letivo"
                min="1901"
                max="2155"
                required
                value="<?= htmlspecialchars(
                    $dados['ano_letivo'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
        </div>

        <div>
            <label for="data_reserva">Data da reserva</label>
            <input
                type="date"
                id="data_reserva"
                name="data_reserva"
                required
                value="<?= htmlspecialchars(
                    $dados['data_reserva'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
        </div>

        <button type="submit">Registrar solicitação</button>
    </form>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>