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

$consultaEstoquePermitida =
    funcionarioPode('estoque.consultar')
    || funcionarioPode('estoque.gerenciar');

$tituloPagina = 'Produtos - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Produtos</h1>

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
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>