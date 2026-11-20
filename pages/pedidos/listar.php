<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('pedidos.consultar');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirPermissao('servicos.gerenciar');
}

require_once __DIR__ . '/../../config/conexao.php';

$erroServico = '';
$nomeServico = '';
$descricaoServico = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao !== 'cadastrar_servico') {
        http_response_code(400);
        exit('Ação inválida.');
    }

    $nomeRecebido = $_POST['nome_servico'] ?? '';
    $descricaoRecebida = $_POST['descricao_servico'] ?? '';

    $nomeServico = is_string($nomeRecebido)
        ? trim($nomeRecebido)
        : '';

    $descricaoServico = is_string($descricaoRecebida)
        ? trim($descricaoRecebida)
        : '';

    if ($nomeServico === '') {
        $erroServico = 'Informe o nome do serviço.';
    } elseif (
        preg_match('/\A.{1,100}\z/us', $nomeServico) !== 1
        || preg_match('/\A.{0,255}\z/us', $descricaoServico) !== 1
    ) {
        $erroServico = 'Confira o tamanho do nome e da descrição.';
    } else {
        try {
            $sqlCadastro = 'INSERT INTO servicos_impressao
                                (nome, descricao)
                            VALUES
                                (:nome, :descricao)';

            $consultaCadastro = $pdo->prepare($sqlCadastro);
            $consultaCadastro->execute([
                'nome' => $nomeServico,
                'descricao' => $descricaoServico,
            ]);

            $_SESSION['mensagem_servico'] = 'Serviço cadastrado.';
            header('Location: listar.php');
            exit;
        } catch (PDOException $erroBanco) {
            $erroServico = 'Não foi possível cadastrar o serviço.';
        }
    }
}

$sql = 'SELECT
            p.id,
            p.nome_solicitante,
            p.sala,
            p.curso_cargo,
            s.nome AS servico,
            p.quantidade,
            p.formato,
            p.prazo,
            p.valor_cobrado,
            p.status,
            f.nome AS funcionario_responsavel
        FROM pedidos p
        INNER JOIN servicos_impressao s ON s.id = p.servico_id
        INNER JOIN funcionarios f ON f.id = p.funcionario_id
        ORDER BY p.id DESC';

$pedidos = $pdo->query($sql)->fetchAll();

$servicos = [];

if (funcionarioPode('servicos.gerenciar')) {
    $sqlServicos = 'SELECT id, nome, descricao, ativo
                    FROM servicos_impressao
                    ORDER BY nome ASC';

    $servicos = $pdo->query($sqlServicos)->fetchAll();
}

$mensagemServico = $_SESSION['mensagem_servico'] ?? '';
unset($_SESSION['mensagem_servico']);

$tituloPagina = 'Pedidos - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Pedidos de impressão</h1>

<?php if (funcionarioPode('servicos.gerenciar')): ?>
    <section>
        <h2>Serviços de impressão</h2>

        <?php if ($mensagemServico !== ''): ?>
            <p role="status">
                <?= htmlspecialchars(
                    $mensagemServico,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>
        <?php endif; ?>

        <?php if ($erroServico !== ''): ?>
            <p role="alert">
                <?= htmlspecialchars(
                    $erroServico,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>
        <?php endif; ?>

        <form method="post" action="listar.php">
            <input
                type="hidden"
                name="acao"
                value="cadastrar_servico"
            >

            <div>
                <label for="nome_servico">Nome do serviço</label>
                <input
                    type="text"
                    id="nome_servico"
                    name="nome_servico"
                    maxlength="100"
                    value="<?= htmlspecialchars(
                        $nomeServico,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </div>

            <div>
                <label for="descricao_servico">Descrição</label>
                <textarea
                    id="descricao_servico"
                    name="descricao_servico"
                    maxlength="255"
                ><?= htmlspecialchars(
                    $descricaoServico,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?></textarea>
            </div>

            <button type="submit">Cadastrar serviço</button>
        </form>

        <?php if (count($servicos) === 0): ?>
            <p>Nenhum serviço cadastrado.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($servicos as $servico): ?>
                    <li>
                        <strong>
                            <?= htmlspecialchars(
                                $servico['nome'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>
                        —
                        <?= htmlspecialchars(
                            $servico['descricao'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                        (<?= (int) $servico['ativo'] === 1
                            ? 'Ativo'
                            : 'Inativo' ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php if (count($pedidos) === 0): ?>
    <p>Nenhum pedido cadastrado.</p>
<?php else: ?>
    <?php foreach ($pedidos as $pedido): ?>
        <section>
            <h2>Pedido #<?= (int) $pedido['id'] ?></h2>

            <dl>
                <dt>Solicitante</dt>
                <dd><?= htmlspecialchars($pedido['nome_solicitante'], ENT_QUOTES, 'UTF-8') ?></dd>

                <dt>Sala</dt>
                <dd><?= htmlspecialchars($pedido['sala'] ?? 'Não informada', ENT_QUOTES, 'UTF-8') ?></dd>

                <dt>Curso ou cargo</dt>
                <dd><?= htmlspecialchars($pedido['curso_cargo'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></dd>

                <dt>Serviço</dt>
                <dd><?= htmlspecialchars($pedido['servico'], ENT_QUOTES, 'UTF-8') ?></dd>

                <dt>Quantidade</dt>
                <dd><?= (int) $pedido['quantidade'] ?></dd>

                <dt>Formato</dt>
                <dd><?= htmlspecialchars($pedido['formato'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></dd>

                <dt>Prazo</dt>
                <dd><?= htmlspecialchars($pedido['prazo'] ?? 'Não definido', ENT_QUOTES, 'UTF-8') ?></dd>

                <dt>Valor cobrado</dt>
                <dd><?= number_format((float) $pedido['valor_cobrado'], 2, ',', '.') ?></dd>

                <dt>Status</dt>
                <dd><?= htmlspecialchars($pedido['status'], ENT_QUOTES, 'UTF-8') ?></dd>

                <dt>Funcionário responsável</dt>
                <dd><?= htmlspecialchars($pedido['funcionario_responsavel'], ENT_QUOTES, 'UTF-8') ?></dd>
            </dl>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>