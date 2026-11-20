<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('pedidos.consultar');

require_once __DIR__ . '/../../config/conexao.php';

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

$tituloPagina = 'Pedidos - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Pedidos de impressão</h1>

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