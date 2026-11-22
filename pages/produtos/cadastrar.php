<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('produtos.editar');

require_once __DIR__ . '/../../config/conexao.php';

$erro = '';
$dados = [
    'nome' => '',
    'tipo' => '',
    'preco' => '',
    'estoque_minimo' => '0',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dados as $campo => $valorAtual) {
        $valorRecebido = $_POST[$campo] ?? '';

        $dados[$campo] = is_string($valorRecebido)
            ? trim($valorRecebido)
            : '';
    }

    $estoqueMinimo = filter_var(
        $dados['estoque_minimo'],
        FILTER_VALIDATE_INT
    );

    if ($dados['nome'] === '') {
        $erro = 'Informe o nome do produto.';
    } elseif (
        preg_match('/\A.{1,100}\z/us', $dados['nome']) !== 1
    ) {
        $erro = 'O nome deve ter até 100 caracteres.';
    } elseif (
        !in_array($dados['tipo'], ['revenda', 'insumo'], true)
    ) {
        $erro = 'Selecione um tipo de produto válido.';
    } elseif (
        $estoqueMinimo === false
        || $estoqueMinimo < 0
    ) {
        $erro = 'O estoque mínimo deve ser um número inteiro igual ou maior que zero.';
    } elseif (
        $dados['tipo'] === 'revenda'
        && $dados['preco'] === ''
    ) {
        $erro = 'Informe o preço do produto de revenda.';
    } elseif (
        $dados['preco'] !== ''
        && preg_match(
            '/\A\d{1,8}(?:\.\d{1,2})?\z/',
            $dados['preco']
        ) !== 1
    ) {
        $erro = 'Informe um preço válido, com até duas casas decimais.';
    }

    if ($erro === '') {
        try {
            $sql = 'INSERT INTO produtos (
                        nome,
                        tipo,
                        preco,
                        estoque_minimo
                    ) VALUES (
                        :nome,
                        :tipo,
                        :preco,
                        :estoque_minimo
                    )';

            $consulta = $pdo->prepare($sql);
            $consulta->execute([
                'nome' => $dados['nome'],
                'tipo' => $dados['tipo'],
                'preco' => $dados['preco'] !== ''
                    ? $dados['preco']
                    : null,
                'estoque_minimo' => $estoqueMinimo,
            ]);

            $_SESSION['mensagem_produto'] = 'Produto cadastrado.';
            header('Location: listar.php');
            exit;
        } catch (PDOException $erroBanco) {
            $erro = 'Não foi possível cadastrar o produto.';
        }
    }
}

$tituloPagina = 'Cadastrar produto - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Cadastrar produto</h1>

<?php if ($erro !== ''): ?>
    <p role="alert">
        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="post" action="cadastrar.php">
    <div>
        <label for="nome">Nome</label>
        <input
            type="text"
            id="nome"
            name="nome"
            maxlength="100"
            value="<?= htmlspecialchars(
                $dados['nome'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            required
        >
    </div>

    <div>
        <label for="tipo">Tipo</label>
        <select id="tipo" name="tipo" required>
            <option value="">Selecione</option>
            <option
                value="revenda"
                <?= $dados['tipo'] === 'revenda' ? 'selected' : '' ?>
            >
                Revenda
            </option>
            <option
                value="insumo"
                <?= $dados['tipo'] === 'insumo' ? 'selected' : '' ?>
            >
                Insumo
            </option>
        </select>
    </div>

    <div>
        <label for="preco">Preço</label>
        <input
            type="number"
            id="preco"
            name="preco"
            min="0"
            step="0.01"
            value="<?= htmlspecialchars(
                $dados['preco'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >
        <small>
            Obrigatório para produtos de revenda. Use ponto para separar centavos.
        </small>
    </div>

    <div>
        <label for="estoque_minimo">Estoque mínimo</label>
        <input
            type="number"
            id="estoque_minimo"
            name="estoque_minimo"
            min="0"
            step="1"
            value="<?= htmlspecialchars(
                $dados['estoque_minimo'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            required
        >
    </div>

    <p>O produto será cadastrado ativo e com estoque atual zero.</p>

    <button type="submit">Cadastrar produto</button>
</form>

<p>
    <a href="listar.php">Voltar para produtos</a>
</p>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>