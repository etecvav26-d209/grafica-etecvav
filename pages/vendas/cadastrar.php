<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('vendas.registrar');

require_once __DIR__ . '/../../config/conexao.php';

$erro = '';
$quantidades = [];

$sqlProdutos = 'SELECT id, nome, preco, estoque_atual
                FROM produtos
                WHERE tipo = :tipo
                  AND ativo = 1
                  AND preco IS NOT NULL
                  AND estoque_atual > 0
                ORDER BY nome ASC';

$consultaProdutos = $pdo->prepare($sqlProdutos);
$consultaProdutos->execute(['tipo' => 'revenda']);
$produtos = $consultaProdutos->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantidadesRecebidas = $_POST['quantidades'] ?? [];

    if (!is_array($quantidadesRecebidas)) {
        $erro = 'Dados dos produtos inválidos.';
    } else {
        foreach ($quantidadesRecebidas as $produtoIdRecebido => $quantidadeRecebida) {
            $produtoIdTexto = (string) $produtoIdRecebido;

            if (preg_match('/\A[1-9]\d*\z/', $produtoIdTexto) !== 1) {
                $erro = 'Foi informado um produto inválido.';
                break;
            }

            if (!is_string($quantidadeRecebida)) {
                $erro = 'Foi informada uma quantidade inválida.';
                break;
            }

            $quantidade = filter_var(
                $quantidadeRecebida,
                FILTER_VALIDATE_INT
            );

            if ($quantidade === false || $quantidade < 0) {
                $erro = 'As quantidades devem ser números inteiros iguais ou maiores que zero.';
                break;
            }

            if ($quantidade > 0) {
                $quantidades[(int) $produtoIdTexto] = $quantidade;
            }
        }
    }

    if ($erro === '' && count($quantidades) === 0) {
        $erro = 'Informe a quantidade de pelo menos um produto.';
    }

    if ($erro === '') {
        ksort($quantidades, SORT_NUMERIC);

        $itensVenda = [];
        $totalCentavos = 0;
        $limiteTotalCentavos = 9999999999;

        try {
            $pdo->beginTransaction();

            $sqlProduto = 'SELECT id, nome, preco, estoque_atual
                           FROM produtos
                           WHERE id = :id
                             AND tipo = :tipo
                             AND ativo = 1
                             AND preco IS NOT NULL
                           FOR UPDATE';

            $consultaProduto = $pdo->prepare($sqlProduto);

            foreach ($quantidades as $produtoId => $quantidade) {
                $consultaProduto->execute([
                    'id' => $produtoId,
                    'tipo' => 'revenda',
                ]);

                $produto = $consultaProduto->fetch();

                if (!$produto) {
                    $erro = 'Um dos produtos selecionados não está disponível para venda.';
                    break;
                }

                $estoqueAtual = (int) $produto['estoque_atual'];

                if ($quantidade > $estoqueAtual) {
                    $erro = 'Estoque insuficiente para o produto '
                        . $produto['nome']
                        . '. Disponível: '
                        . $estoqueAtual
                        . '.';
                    break;
                }

                $partesPreco = explode('.', (string) $produto['preco'], 2);
                $parteInteira = (int) $partesPreco[0];
                $parteDecimal = str_pad(
                    $partesPreco[1] ?? '',
                    2,
                    '0'
                );
                $precoCentavos = ($parteInteira * 100)
                    + (int) substr($parteDecimal, 0, 2);

                if (
                    $precoCentavos > 0
                    && $quantidade > intdiv(
                        $limiteTotalCentavos - $totalCentavos,
                        $precoCentavos
                    )
                ) {
                    $erro = 'O total da venda ultrapassa o limite aceito pelo banco.';
                    break;
                }

                $subtotalCentavos = $precoCentavos * $quantidade;
                $totalCentavos += $subtotalCentavos;

                $itensVenda[] = [
                    'produto_id' => (int) $produto['id'],
                    'nome' => $produto['nome'],
                    'quantidade' => $quantidade,
                    'preco' => (string) $produto['preco'],
                ];
            }

            if ($erro === '') {
                $total = sprintf(
                    '%d.%02d',
                    intdiv($totalCentavos, 100),
                    $totalCentavos % 100
                );

                $sqlVenda = 'INSERT INTO vendas (
                                 funcionario_id,
                                 total
                             ) VALUES (
                                 :funcionario_id,
                                 :total
                             )';

                $consultaVenda = $pdo->prepare($sqlVenda);
                $consultaVenda->execute([
                    'funcionario_id' => $_SESSION['funcionario_id'],
                    'total' => $total,
                ]);

                $vendaId = (int) $pdo->lastInsertId();

                $sqlItem = 'INSERT INTO itens_venda (
                                venda_id,
                                produto_id,
                                quantidade,
                                preco_unitario
                            ) VALUES (
                                :venda_id,
                                :produto_id,
                                :quantidade,
                                :preco_unitario
                            )';

                $consultaItem = $pdo->prepare($sqlItem);

                $sqlBaixaEstoque = 'UPDATE produtos
                                    SET estoque_atual = estoque_atual - :quantidade
                                    WHERE id = :produto_id
                                      AND estoque_atual >= :quantidade';

                $consultaBaixaEstoque = $pdo->prepare($sqlBaixaEstoque);

                $sqlMovimentacao = 'INSERT INTO movimentacoes_estoque (
                                        produto_id,
                                        funcionario_id,
                                        tipo,
                                        quantidade,
                                        motivo
                                    ) VALUES (
                                        :produto_id,
                                        :funcionario_id,
                                        :tipo,
                                        :quantidade,
                                        :motivo
                                    )';

                $consultaMovimentacao = $pdo->prepare($sqlMovimentacao);

                foreach ($itensVenda as $item) {
                    $consultaItem->execute([
                        'venda_id' => $vendaId,
                        'produto_id' => $item['produto_id'],
                        'quantidade' => $item['quantidade'],
                        'preco_unitario' => $item['preco'],
                    ]);

                    $consultaBaixaEstoque->execute([
                        'quantidade' => $item['quantidade'],
                        'produto_id' => $item['produto_id'],
                    ]);

                    if ($consultaBaixaEstoque->rowCount() !== 1) {
                        throw new RuntimeException(
                            'Não foi possível atualizar o estoque.'
                        );
                    }

                    $consultaMovimentacao->execute([
                        'produto_id' => $item['produto_id'],
                        'funcionario_id' => $_SESSION['funcionario_id'],
                        'tipo' => 'saida',
                        'quantidade' => $item['quantidade'],
                        'motivo' => 'Venda #' . $vendaId,
                    ]);
                }

                $pdo->commit();

                $_SESSION['mensagem_venda'] = 'Venda registrada.';
                header('Location: listar.php');
                exit;
            }

            $pdo->rollBack();
        } catch (Throwable $erroBanco) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            if ($erro === '') {
                $erro = 'Não foi possível registrar a venda.';
            }
        }
    }
}

$tituloPagina = 'Registrar venda - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Registrar venda</h1>

<?php if ($erro !== ''): ?>
    <p role="alert">
        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<?php if (count($produtos) === 0): ?>
    <p>
        Não há produtos de revenda ativos, com preço cadastrado e estoque disponível.
    </p>
<?php else: ?>
    <form method="post" action="cadastrar.php">
        <p>
            Informe a quantidade de cada produto. Deixe zero para não incluí-lo.
        </p>

        <?php foreach ($produtos as $produto): ?>
            <?php
            $produtoId = (int) $produto['id'];
            $quantidadeAtual = $quantidades[$produtoId] ?? 0;
            ?>

            <div>
                <label for="quantidade_<?= $produtoId ?>">
                    <?= htmlspecialchars(
                        $produto['nome'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                    — preço unitário:
                    <?= number_format(
                        (float) $produto['preco'],
                        2,
                        ',',
                        '.'
                    ) ?>
                    — estoque:
                    <?= (int) $produto['estoque_atual'] ?>
                </label>

                <input
                    type="number"
                    id="quantidade_<?= $produtoId ?>"
                    name="quantidades[<?= $produtoId ?>]"
                    min="0"
                    max="<?= (int) $produto['estoque_atual'] ?>"
                    step="1"
                    value="<?= $quantidadeAtual ?>"
                >
            </div>
        <?php endforeach; ?>

        <button type="submit">Registrar venda</button>
    </form>
<?php endif; ?>

<p>
    <a href="listar.php">Voltar para vendas</a>
</p>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>