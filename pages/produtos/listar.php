<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('produtos.consultar');

require_once __DIR__ . '/../../config/conexao.php';

$sql = 'SELECT
            id,
            nome,
            tipo,
            preco,
            estoque_atual,
            estoque_minimo,
            ativo
        FROM produtos
        ORDER BY nome ASC';

$produtos = $pdo->query($sql)->fetchAll();

$mensagemProduto = $_SESSION['mensagem_produto'] ?? '';
unset($_SESSION['mensagem_produto']);

$consultaEstoquePermitida =
    funcionarioPode('estoque.consultar')
    || funcionarioPode('estoque.gerenciar');

$tituloPagina = 'Produtos - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Produtos</h1>

<?php if ($mensagemProduto !== ''): ?>
    <p role="status">
        <?= htmlspecialchars(
            $mensagemProduto,
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>
<?php endif; ?>

<?php if (funcionarioPode('produtos.editar')): ?>
    <p>
        <a href="cadastrar.php">Cadastrar produto</a>
    </p>
<?php endif; ?>

<?php if (count($produtos) === 0): ?>
    <p>Nenhum produto cadastrado.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Preço</th>
                <th>Estoque atual</th>
                <th>Estoque mínimo</th>
                <th>Situação</th>
                <?php if (funcionarioPode('produtos.editar')): ?>
                    <th>Ações</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
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
                    <td>
                        <?= $produto['preco'] === null
                            ? 'Não definido'
                            : number_format(
                                (float) $produto['preco'],
                                2,
                                ',',
                                '.'
                            ) ?>
                    </td>
                    <td><?= (int) $produto['estoque_atual'] ?></td>
                    <td><?= (int) $produto['estoque_minimo'] ?></td>
                    <td>
                        <?= (int) $produto['ativo'] === 1
                            ? 'Ativo'
                            : 'Inativo' ?>

                        <?php if (
                            $consultaEstoquePermitida
                            && (int) $produto['ativo'] === 1
                            && (int) $produto['estoque_atual']
                                <= (int) $produto['estoque_minimo']
                        ): ?>
                            — Estoque baixo
                        <?php endif; ?>
                    </td>

                    <?php if (funcionarioPode('produtos.editar')): ?>
                        <td>
                            <a href="editar.php?id=<?= (int) $produto['id'] ?>">
                                Editar
                            </a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>