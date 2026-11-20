<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('pedidos.cadastrar');

require_once __DIR__ . '/../../config/conexao.php';

$erro = '';
$dados = [
    'nome_solicitante' => '',
    'sala' => '',
    'curso_cargo' => '',
    'servico_id' => '',
    'quantidade' => '',
    'formato' => '',
    'prazo' => '',
    'valor_cobrado' => '',
];

$sqlServicos = 'SELECT id, nome
                FROM servicos_impressao
                WHERE ativo = 1
                ORDER BY nome ASC';

$servicos = $pdo->query($sqlServicos)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dados as $campo => $valorAtual) {
        $valorRecebido = $_POST[$campo] ?? '';

        $dados[$campo] = is_string($valorRecebido)
            ? trim($valorRecebido)
            : '';
    }

    $quantidade = filter_var(
        $dados['quantidade'],
        FILTER_VALIDATE_INT
    );

    $servicoId = filter_var(
        $dados['servico_id'],
        FILTER_VALIDATE_INT
    );

    $prazo = null;

    if ($dados['prazo'] !== '') {
        $dataRecebida = DateTime::createFromFormat(
            '!Y-m-d',
            $dados['prazo']
        );

        if (
            !$dataRecebida
            || $dataRecebida->format('Y-m-d') !== $dados['prazo']
        ) {
            $erro = 'Informe um prazo válido.';
        } else {
            $prazo = $dados['prazo'];
        }
    }

    if ($erro === '' && $dados['nome_solicitante'] === '') {
        $erro = 'Informe o nome do solicitante.';
    } elseif (
        $erro === ''
        && preg_match('/\A.{1,100}\z/us', $dados['nome_solicitante']) !== 1
    ) {
        $erro = 'O nome do solicitante deve ter até 100 caracteres.';
    } elseif (
        $erro === ''
        && $dados['sala'] !== ''
        && preg_match('/\A.{1,30}\z/us', $dados['sala']) !== 1
    ) {
        $erro = 'A sala deve ter até 30 caracteres.';
    } elseif (
        $erro === ''
        && $dados['curso_cargo'] !== ''
        && preg_match('/\A.{1,100}\z/us', $dados['curso_cargo']) !== 1
    ) {
        $erro = 'Curso ou cargo deve ter até 100 caracteres.';
    } elseif ($erro === '' && $servicoId === false) {
        $erro = 'Selecione um serviço válido.';
    } elseif (
        $erro === ''
        && (
            $quantidade === false
            || $quantidade < 1
        )
    ) {
        $erro = 'A quantidade deve ser um número inteiro maior que zero.';
    } elseif (
        $erro === ''
        && $dados['formato'] !== ''
        && preg_match('/\A.{1,50}\z/us', $dados['formato']) !== 1
    ) {
        $erro = 'O formato deve ter até 50 caracteres.';
    } elseif (
        $erro === ''
        && preg_match('/\A\d{1,8}(?:\.\d{1,2})?\z/', $dados['valor_cobrado']) !== 1
    ) {
        $erro = 'Informe um valor válido, com até duas casas decimais.';
    }

    if ($erro === '') {
        $sqlServicoAtivo = 'SELECT id
                            FROM servicos_impressao
                            WHERE id = :id
                              AND ativo = 1';

        $consultaServico = $pdo->prepare($sqlServicoAtivo);
        $consultaServico->execute(['id' => $servicoId]);

        if (!$consultaServico->fetch()) {
            $erro = 'O serviço selecionado não está disponível.';
        }
    }

    if ($erro === '') {
        try {
            $sqlCadastro = 'INSERT INTO pedidos (
                                nome_solicitante,
                                sala,
                                curso_cargo,
                                servico_id,
                                quantidade,
                                formato,
                                prazo,
                                valor_cobrado,
                                status,
                                funcionario_id
                            ) VALUES (
                                :nome_solicitante,
                                :sala,
                                :curso_cargo,
                                :servico_id,
                                :quantidade,
                                :formato,
                                :prazo,
                                :valor_cobrado,
                                :status,
                                :funcionario_id
                            )';

            $consultaCadastro = $pdo->prepare($sqlCadastro);
            $consultaCadastro->execute([
                'nome_solicitante' => $dados['nome_solicitante'],
                'sala' => $dados['sala'] !== ''
                    ? $dados['sala']
                    : null,
                'curso_cargo' => $dados['curso_cargo'] !== ''
                    ? $dados['curso_cargo']
                    : null,
                'servico_id' => $servicoId,
                'quantidade' => $quantidade,
                'formato' => $dados['formato'] !== ''
                    ? $dados['formato']
                    : null,
                'prazo' => $prazo,
                'valor_cobrado' => $dados['valor_cobrado'],
                'status' => 'recebido',
                'funcionario_id' => $_SESSION['funcionario_id'],
            ]);

            $_SESSION['mensagem_pedido'] = 'Pedido cadastrado.';
            header('Location: listar.php');
            exit;
        } catch (PDOException $erroBanco) {
            $erro = 'Não foi possível cadastrar o pedido.';
        }
    }
}

$tituloPagina = 'Cadastrar pedido - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Cadastrar pedido de impressão</h1>

<?php if ($erro !== ''): ?>
    <p role="alert">
        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<?php if (count($servicos) === 0): ?>
    <p>Não há serviços ativos para selecionar.</p>
<?php else: ?>
    <form method="post" action="cadastrar.php">
        <div>
            <label for="nome_solicitante">Nome do solicitante</label>
            <input
                type="text"
                id="nome_solicitante"
                name="nome_solicitante"
                maxlength="100"
                value="<?= htmlspecialchars(
                    $dados['nome_solicitante'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                required
            >
        </div>

        <div>
            <label for="sala">Sala</label>
            <input
                type="text"
                id="sala"
                name="sala"
                maxlength="30"
                value="<?= htmlspecialchars(
                    $dados['sala'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
        </div>

        <div>
            <label for="curso_cargo">Curso ou cargo</label>
            <input
                type="text"
                id="curso_cargo"
                name="curso_cargo"
                maxlength="100"
                value="<?= htmlspecialchars(
                    $dados['curso_cargo'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
        </div>

        <div>
            <label for="servico_id">Serviço</label>
            <select id="servico_id" name="servico_id" required>
                <option value="">Selecione</option>

                <?php foreach ($servicos as $servico): ?>
                    <option
                        value="<?= (int) $servico['id'] ?>"
                        <?= $dados['servico_id'] === (string) $servico['id']
                            ? 'selected'
                            : '' ?>
                    >
                        <?= htmlspecialchars(
                            $servico['nome'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="quantidade">Quantidade</label>
            <input
                type="number"
                id="quantidade"
                name="quantidade"
                min="1"
                step="1"
                value="<?= htmlspecialchars(
                    $dados['quantidade'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                required
            >
        </div>

        <div>
            <label for="formato">Formato</label>
            <input
                type="text"
                id="formato"
                name="formato"
                maxlength="50"
                value="<?= htmlspecialchars(
                    $dados['formato'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
        </div>

        <div>
            <label for="prazo">Prazo</label>
            <input
                type="date"
                id="prazo"
                name="prazo"
                value="<?= htmlspecialchars(
                    $dados['prazo'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
        </div>

        <div>
            <label for="valor_cobrado">Valor cobrado</label>
            <input
                type="number"
                id="valor_cobrado"
                name="valor_cobrado"
                min="0"
                step="0.01"
                value="<?= htmlspecialchars(
                    $dados['valor_cobrado'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                required
            >
        </div>

        <button type="submit">Cadastrar pedido</button>
    </form>
<?php endif; ?>

<p>
    <a href="listar.php">Voltar para pedidos</a>
</p>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
