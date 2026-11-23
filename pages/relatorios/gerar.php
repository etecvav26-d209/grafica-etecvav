<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('relatorios.gerar');

require_once __DIR__ . '/../../config/conexao.php';

$mensagemErro = '';
$mensagemSucesso = $_SESSION['mensagem_relatorio'] ?? '';
unset($_SESSION['mensagem_relatorio']);

$dados = [
    'tipo' => '',
    'data_inicio' => '',
    'data_fim' => '',
];

if (empty($_SESSION['token_relatorio'])) {
    $_SESSION['token_relatorio'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dados as $campo => $valorAtual) {
        if (isset($_POST[$campo]) && is_string($_POST[$campo])) {
            $dados[$campo] = trim($_POST[$campo]);
        }
    }

    $tokenEnviado = $_POST['token_relatorio'] ?? '';

    if (
        !is_string($tokenEnviado)
        || !hash_equals($_SESSION['token_relatorio'], $tokenEnviado)
    ) {
        $mensagemErro = 'Não foi possível validar o formulário. Tente novamente.';
    } else {
        $tipo = $dados['tipo'];
        $dataInicio = $dados['data_inicio'];
        $dataFim = $dados['data_fim'];

        $dataInicioValida =
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataInicio) === 1
            && checkdate(
                (int) substr($dataInicio, 5, 2),
                (int) substr($dataInicio, 8, 2),
                (int) substr($dataInicio, 0, 4)
            );

        $dataFimValida =
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataFim) === 1
            && checkdate(
                (int) substr($dataFim, 5, 2),
                (int) substr($dataFim, 8, 2),
                (int) substr($dataFim, 0, 4)
            );

        if (
            !in_array($tipo, ['pedidos', 'vendas'], true)
            || !$dataInicioValida
            || !$dataFimValida
            || $dataInicio > $dataFim
        ) {
            $mensagemErro =
                'Informe o tipo e um período válido, com início anterior ao fim.';
        } else {
            try {
                $pdo->beginTransaction();

                if ($tipo === 'vendas') {
                    $sqlResumo = 'SELECT
                                      COUNT(*) AS total_registros,
                                      COALESCE(SUM(total), 0) AS total_valor
                                  FROM vendas
                                  WHERE DATE(data_venda)
                                      BETWEEN :data_inicio AND :data_fim';
                } else {
                    $sqlResumo = 'SELECT
                                      COUNT(*) AS total_registros,
                                      COALESCE(SUM(valor_cobrado), 0)
                                          AS total_valor
                                  FROM pedidos
                                  WHERE prazo
                                      BETWEEN :data_inicio AND :data_fim';
                }

                $consultaResumo = $pdo->prepare($sqlResumo);
                $consultaResumo->execute([
                    'data_inicio' => $dataInicio,
                    'data_fim' => $dataFim,
                ]);

                $resumo = $consultaResumo->fetch();

                $inserirRelatorio = $pdo->prepare(
                    'INSERT INTO relatorios (
                        tipo,
                        data_inicio,
                        data_fim,
                        total_registros,
                        total_valor,
                        funcionario_id
                    ) VALUES (
                        :tipo,
                        :data_inicio,
                        :data_fim,
                        :total_registros,
                        :total_valor,
                        :funcionario_id
                    )'
                );

                $inserirRelatorio->execute([
                    'tipo' => $tipo,
                    'data_inicio' => $dataInicio,
                    'data_fim' => $dataFim,
                    'total_registros' =>
                        (int) $resumo['total_registros'],
                    'total_valor' => $resumo['total_valor'],
                    'funcionario_id' =>
                        (int) $_SESSION['funcionario_id'],
                ]);

                $pdo->commit();

                $_SESSION['mensagem_relatorio'] =
                    'Relatório gerado e salvo com sucesso.';
                $_SESSION['token_relatorio'] = bin2hex(random_bytes(32));

                header('Location: gerar.php');
                exit;
            } catch (PDOException $erro) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                $mensagemErro =
                    'Não foi possível gerar o relatório. Tente novamente.';
            }
        }
    }
}

$tituloPagina = 'Gerar relatório - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Gerar relatório</h1>

<?php if ($mensagemSucesso !== ''): ?>
    <p role="status">
        <?= htmlspecialchars($mensagemSucesso, ENT_QUOTES, 'UTF-8') ?>
    </p>
    <p>
        <a href="listar.php">Consultar relatórios</a>
    </p>
<?php endif; ?>

<?php if ($mensagemErro !== ''): ?>
    <p role="alert">
        <?= htmlspecialchars($mensagemErro, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="post">
    <input
        type="hidden"
        name="token_relatorio"
        value="<?= htmlspecialchars(
            $_SESSION['token_relatorio'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
    >

    <div>
        <label for="tipo">Tipo de relatório</label>
        <select id="tipo" name="tipo" required>
            <option value="">Selecione</option>
            <option
                value="pedidos"
                <?= $dados['tipo'] === 'pedidos' ? 'selected' : '' ?>
            >
                Pedidos
            </option>
            <option
                value="vendas"
                <?= $dados['tipo'] === 'vendas' ? 'selected' : '' ?>
            >
                Vendas
            </option>
        </select>
    </div>

    <div>
        <label for="data_inicio">Data inicial</label>
        <input
            type="date"
            id="data_inicio"
            name="data_inicio"
            required
            value="<?= htmlspecialchars(
                $dados['data_inicio'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >
    </div>

    <div>
        <label for="data_fim">Data final</label>
        <input
            type="date"
            id="data_fim"
            name="data_fim"
            required
            value="<?= htmlspecialchars(
                $dados['data_fim'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >
    </div>

    <button type="submit">Gerar relatório</button>
</form>

<p>
    <a href="listar.php">Voltar à lista de relatórios</a>
</p>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>