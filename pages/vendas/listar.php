<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('vendas.consultar');

require_once __DIR__ . '/../../config/conexao.php';

$sql = 'SELECT
            v.id AS venda_id,
            v.data_venda,
            v.total,
            f.nome AS funcionario,
            iv.id AS item_id,
            iv.quantidade,
            iv.preco_unitario,
            p.nome AS produto
        FROM vendas v
        INNER JOIN funcionarios f
            ON f.id = v.funcionario_id
        LEFT JOIN itens_venda iv
            ON iv.venda_id = v.id
        LEFT JOIN produtos p
            ON p.id = iv.produto_id
        ORDER BY v.data_venda DESC, v.id DESC, iv.id ASC';

$consulta = $pdo->query($sql);
$linhas = $consulta->fetchAll();

$vendas = [];

foreach ($linhas as $linha) {
    $vendaId = (int) $linha['venda_id'];

    if (!isset($vendas[$vendaId])) {
        $vendas[$vendaId] = [
            'id' => $vendaId,
            'data_venda' => $linha['data_venda'],
            'total' => $linha['total'],
            'funcionario' => $linha['funcionario'],
            'itens' => [],
        ];
    }

    if ($linha['item_id'] !== null) {
        $vendas[$vendaId]['itens'][] = [
            'produto' => $linha['produto'],
            'quantidade' => $linha['quantidade'],
            'preco_unitario' => $linha['preco_unitario'],
        ];
    }
}

$mensagemVenda = $_SESSION['mensagem_venda'] ?? '';
unset($_SESSION['mensagem_venda']);

$tituloPagina = 'Vendas - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Vendas</h1>

<?php if ($mensagemVenda !== ''): ?>
    <p role="status">
        <?= htmlspecialchars(
            $mensagemVenda,
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>
<?php endif; ?>

<?php if (funcionarioPode('vendas.registrar')): ?>
    <p>
        <a href="cadastrar.php">Registrar venda</a>
    </p>
<?php endif; ?>

<?php if (count($vendas) === 0): ?>
    <p>Nenhuma venda registrada.</p>
<?php else: ?>
    <?php foreach ($vendas as $venda): ?>
        <section>
            <h2>Venda #<?= $venda['id'] ?></h2>

            <dl>
                <dt>Data</dt>
                <dd>
                    <?= htmlspecialchars(
                        $venda['data_venda'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </dd>

                <dt>Funcionário</dt>
                <dd>
                    <?= htmlspecialchars(
                        $venda['funcionario'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </dd>

                <dt>Total</dt>
                <dd>
                    <?= number_format(
                        (float) $venda['total'],
                        2,
                        ',',
                        '.'
                    ) ?>
                </dd>
            </dl>

            <h3>Itens</h3>

            <?php if (count($venda['itens']) === 0): ?>
                <p>Esta venda não possui itens registrados.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($venda['itens'] as $item): ?>
                        <li>
                            <?= htmlspecialchars(
                                $item['produto'] ?? 'Produto indisponível',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                            — quantidade:
                            <?= (int) $item['quantidade'] ?>,
                            preço unitário:
                            <?= number_format(
                                (float) $item['preco_unitario'],
                                2,
                                ',',
                                '.'
                            ) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>