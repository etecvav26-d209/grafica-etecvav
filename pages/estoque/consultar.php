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

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>