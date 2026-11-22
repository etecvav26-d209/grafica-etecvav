<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('manutencoes.registrar');

require_once __DIR__ . '/../../config/conexao.php';

$erro = '';
$equipamento = '';
$descricaoProblema = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipamentoRecebido = $_POST['equipamento'] ?? '';
    $descricaoRecebida = $_POST['descricao_problema'] ?? '';

    $equipamento = is_string($equipamentoRecebido)
        ? trim($equipamentoRecebido)
        : '';

    $descricaoProblema = is_string($descricaoRecebida)
        ? trim($descricaoRecebida)
        : '';

    if ($equipamento === '') {
        $erro = 'Informe o equipamento.';
    } elseif (
        preg_match('/\A.{1,100}\z/us', $equipamento) !== 1
    ) {
        $erro = 'O nome do equipamento deve ter até 100 caracteres.';
    } elseif ($descricaoProblema === '') {
        $erro = 'Descreva o problema.';
    } elseif (strlen($descricaoProblema) > 65535) {
        $erro = 'A descrição ultrapassa o tamanho permitido.';
    }

    if ($erro === '') {
        try {
            $dataRegistro = (new DateTimeImmutable(
                'now',
                new DateTimeZone('America/Sao_Paulo')
            ))->format('Y-m-d');

            $sql = 'INSERT INTO manutencoes (
                        equipamento,
                        descricao_problema,
                        data_registro,
                        funcionario_id,
                        status
                    ) VALUES (
                        :equipamento,
                        :descricao_problema,
                        :data_registro,
                        :funcionario_id,
                        :status
                    )';

            $consulta = $pdo->prepare($sql);
            $consulta->execute([
                'equipamento' => $equipamento,
                'descricao_problema' => $descricaoProblema,
                'data_registro' => $dataRegistro,
                'funcionario_id' => $_SESSION['funcionario_id'],
                'status' => 'aberta',
            ]);

            $_SESSION['mensagem_manutencao'] =
                'Ocorrência de manutenção registrada.';

            header('Location: listar.php');
            exit;
        } catch (PDOException $erroBanco) {
            $erro = 'Não foi possível registrar a ocorrência.';
        }
    }
}

$tituloPagina = 'Registrar manutenção - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Registrar ocorrência de manutenção</h1>

<?php if ($erro !== ''): ?>
    <p role="alert">
        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="post" action="cadastrar.php">
    <div>
        <label for="equipamento">Equipamento</label>
        <input
            type="text"
            id="equipamento"
            name="equipamento"
            maxlength="100"
            value="<?= htmlspecialchars(
                $equipamento,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            required
        >
    </div>

    <div>
        <label for="descricao_problema">Descrição do problema</label>
        <textarea
            id="descricao_problema"
            name="descricao_problema"
            required
        ><?= htmlspecialchars(
            $descricaoProblema,
            ENT_QUOTES,
            'UTF-8'
        ) ?></textarea>
    </div>

    <p>
        A ocorrência será registrada com status “aberta”.
    </p>

    <button type="submit">Registrar ocorrência</button>
</form>

<p>
    <a href="listar.php">Voltar para manutenções</a>
</p>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>