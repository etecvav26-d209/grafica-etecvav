<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('relatorios.consultar');

require_once __DIR__ . '/../../config/conexao.php';

$sql = 'SELECT
            r.id,
            r.tipo,
            r.data_inicio,
            r.data_fim,
            r.data_geracao,
            r.total_registros,
            r.total_valor,
            f.nome AS funcionario
        FROM relatorios r
        INNER JOIN funcionarios f
            ON f.id = r.funcionario_id
        ORDER BY r.data_geracao DESC, r.id DESC';

$relatorios = $pdo->query($sql)->fetchAll();

$tituloPagina = 'Relatórios - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Relatórios</h1>

<?php if (funcionarioPode('relatorios.gerar')): ?>
    <p>
        <a href="gerar.php">Gerar relatório</a>
    </p>
<?php endif; ?>

<?php if (count($relatorios) === 0): ?>
    <p>Nenhum relatório cadastrado.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Período inicial</th>
                <th>Período final</th>
                <th>Data de geração</th>
                <th>Total de registros</th>
                <th>Total registrado</th>
                <th>Funcionário responsável</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($relatorios as $relatorio): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars(
                            $relatorio['tipo'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $relatorio['data_inicio'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $relatorio['data_fim'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $relatorio['data_geracao'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td><?= (int) $relatorio['total_registros'] ?></td>
                    <td>
                        <?= number_format(
                            (float) $relatorio['total_valor'],
                            2,
                            ',',
                            '.'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $relatorio['funcionario'],
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