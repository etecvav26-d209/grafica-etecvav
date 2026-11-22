<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('estoque.consultar');

require_once __DIR__ . '/../../config/conexao.php';

$sql = 'SELECT
            id,
            nome,
            tipo,
            estoque_atual,
            estoque_minimo,
            ativo
        FROM produtos
        ORDER BY nome ASC';

$produtos = $pdo->query($sql)->fetchAll();

$quantidadeEstoqueBaixo = 0;

foreach ($produtos as $produto) {
    if (
        (int) $produto['ativo'] === 1
        && (int) $produto['estoque_atual']
            <= (int) $produto['estoque_minimo']
    ) {
        $quantidadeEstoqueBaixo++;
    }
}

$sqlMovimentacoes = 'SELECT
                         m.id,
                         m.data_movimentacao,
                         m.tipo,
                         m.quantidade,
                         m.motivo,
                         p.nome AS produto,
                         f.nome AS funcionario
                     FROM movimentacoes_estoque m
                     INNER JOIN produtos p
                         ON p.id = m.produto_id
                     INNER JOIN funcionarios f
                         ON f.id = m.funcionario_id
                     ORDER BY m.data_movimentacao DESC, m.id DESC';

$movimentacoes = $pdo->query($sqlMovimentacoes)->fetchAll();

$mensagemEstoque = $_SESSION['mensagem_estoque'] ?? '';
unset($_SESSION['mensagem_estoque']);

$tituloPagina = 'Consulta de estoque - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Estoque</h1>

<?php if ($mensagemEstoque !== ''): ?>
    <p role="status">
        <?= htmlspecialchars(
            $mensagemEstoque,
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>
<?php endif; ?>

<?php if (funcionarioPode('estoque.gerenciar')): ?>
    <p>
        <a href="movimentar.php">Movimentar estoque</a>
    </p>
<?php endif; ?>

<p>
    Produtos ativos com estoque igual ou abaixo do mínimo:
    <?= $quantidadeEstoqueBaixo ?>
</p>

<h2>Saldos dos produtos</h2>

<?php if (count($produtos) === 0): ?>
    <p>Nenhum produto cadastrado.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Tipo</th>
                <th>Estoque atual</th>
                <th>Estoque mínimo</th>
                <th>Situação</th>
                <th>Alerta</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <?php
                $ativo = (int) $produto['ativo'] === 1;
                $estoqueAtual = (int) $produto['estoque_atual'];
                $estoqueMinimo = (int) $produto['estoque_minimo'];
                $estoqueBaixo = $ativo
                    && $estoqueAtual <= $estoqueMinimo;
                ?>

                <tr>
                    <td>
                        <?= htmlspecialchars(
                            $produto['nome'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $produto['tipo'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td><?= $estoqueAtual ?></td>
                    <td><?= $estoqueMinimo ?></td>
                    <td><?= $ativo ? 'Ativo' : 'Inativo' ?></td>
                    <td>
                        <?= $estoqueBaixo
                            ? 'Estoque baixo'
                            : 'Dentro do mínimo' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h2>Histórico de movimentações</h2>

<?php if (count($movimentacoes) === 0): ?>
    <p>Nenhuma movimentação registrada.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Produto</th>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Motivo</th>
                <th>Funcionário</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimentacoes as $movimentacao): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars(
                            $movimentacao['data_movimentacao'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $movimentacao['produto'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $movimentacao['tipo'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td><?= (int) $movimentacao['quantidade'] ?></td>
                    <td>
                        <?= htmlspecialchars(
                            $movimentacao['motivo'] ?? 'Não informado',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $movimentacao['funcionario'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>