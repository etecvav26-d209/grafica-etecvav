<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('estoque.gerenciar');

require_once __DIR__ . '/../../config/conexao.php';

$erro = '';
$dados = [
    'produto_id' => '',
    'tipo' => '',
    'quantidade' => '',
    'motivo' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dados as $campo => $valorAtual) {
        $valorRecebido = $_POST[$campo] ?? '';

        $dados[$campo] = is_string($valorRecebido)
            ? trim($valorRecebido)
            : '';
    }

    $produtoId = filter_var(
        $dados['produto_id'],
        FILTER_VALIDATE_INT
    );

    $quantidade = filter_var(
        $dados['quantidade'],
        FILTER_VALIDATE_INT
    );

    if ($produtoId === false || $produtoId < 1) {
        $erro = 'Selecione um produto válido.';
    } elseif (!in_array($dados['tipo'], ['entrada', 'saida'], true)) {
        $erro = 'Selecione entrada ou saída.';
    } elseif (
        $quantidade === false
        || $quantidade < 1
        || $quantidade > 2147483647
    ) {
        $erro = 'Informe uma quantidade inteira maior que zero.';
    } elseif (
        $dados['motivo'] !== ''
        && preg_match('/\A.{1,255}\z/us', $dados['motivo']) !== 1
    ) {
        $erro = 'O motivo deve ter até 255 caracteres.';
    }

    if ($erro === '') {
        try {
            $pdo->beginTransaction();

            $sqlProduto = 'SELECT id, nome, estoque_atual
                           FROM produtos
                           WHERE id = :id
                           FOR UPDATE';

            $consultaProduto = $pdo->prepare($sqlProduto);
            $consultaProduto->execute(['id' => $produtoId]);
            $produto = $consultaProduto->fetch();

            if (!$produto) {
                $erro = 'Produto não encontrado.';
            } else {
                $estoqueAtual = (int) $produto['estoque_atual'];

                if (
                    $dados['tipo'] === 'saida'
                    && $quantidade > $estoqueAtual
                ) {
                    $erro = 'Estoque insuficiente. Saldo atual: '
                        . $estoqueAtual
                        . '.';
                } elseif (
                    $dados['tipo'] === 'entrada'
                    && $quantidade > 2147483647 - $estoqueAtual
                ) {
                    $erro = 'A quantidade ultrapassa o limite do estoque.';
                } else {
                    $novoEstoque = $dados['tipo'] === 'entrada'
                        ? $estoqueAtual + $quantidade
                        : $estoqueAtual - $quantidade;

                    $sqlAtualizacao = 'UPDATE produtos
                                       SET estoque_atual = :estoque
                                       WHERE id = :id';

                    $consultaAtualizacao = $pdo->prepare($sqlAtualizacao);
                    $consultaAtualizacao->execute([
                        'estoque' => $novoEstoque,
                        'id' => $produtoId,
                    ]);

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
                    $consultaMovimentacao->execute([
                        'produto_id' => $produtoId,
                        'funcionario_id' => $_SESSION['funcionario_id'],
                        'tipo' => $dados['tipo'],
                        'quantidade' => $quantidade,
                        'motivo' => $dados['motivo'] !== ''
                            ? $dados['motivo']
                            : null,
                    ]);
                }
            }

            if ($erro !== '') {
                $pdo->rollBack();
            } else {
                $pdo->commit();

                $_SESSION['mensagem_estoque'] = 'Movimentação registrada.';
                header('Location: consultar.php');
                exit;
            }
        } catch (Throwable $erroBanco) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $erro = 'Não foi possível registrar a movimentação.';
        }
    }
}

$sqlProdutos = 'SELECT id, nome, estoque_atual
                FROM produtos
                ORDER BY nome ASC';

$produtos = $pdo->query($sqlProdutos)->fetchAll();

$tituloPagina = 'Movimentar estoque - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Movimentar estoque</h1>

<?php if ($erro !== ''): ?>
    <p role="alert">
        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<?php if (count($produtos) === 0): ?>
    <p>Nenhum produto cadastrado.</p>
<?php else: ?>
    <form method="post" action="movimentar.php">
        <div>
            <label for="produto_id">Produto</label>
            <select id="produto_id" name="produto_id" required>
                <option value="">Selecione</option>

                <?php foreach ($produtos as $produto): ?>
                    <option
                        value="<?= (int) $produto['id'] ?>"
                        <?= $dados['produto_id'] === (string) $produto['id']
                            ? 'selected'
                            : '' ?>
                    >
                        <?= htmlspecialchars(
                            $produto['nome'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                        — estoque atual:
                        <?= (int) $produto['estoque_atual'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="tipo">Tipo de movimentação</label>
            <select id="tipo" name="tipo" required>
                <option value="">Selecione</option>
                <option
                    value="entrada"
                    <?= $dados['tipo'] === 'entrada' ? 'selected' : '' ?>
                >
                    Entrada
                </option>
                <option
                    value="saida"
                    <?= $dados['tipo'] === 'saida' ? 'selected' : '' ?>
                >
                    Saída
                </option>
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
            <label for="motivo">Motivo</label>
            <input
                type="text"
                id="motivo"
                name="motivo"
                maxlength="255"
                value="<?= htmlspecialchars(
                    $dados['motivo'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
        </div>

        <button type="submit">Registrar movimentação</button>
    </form>
<?php endif; ?>

<p>
    <a href="consultar.php">Voltar para estoque</a>
</p>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>