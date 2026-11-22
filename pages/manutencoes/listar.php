<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('manutencoes.consultar');

require_once __DIR__ . '/../../config/conexao.php';

$sql = 'SELECT
            m.id,
            m.equipamento,
            m.descricao_problema,
            m.data_registro,
            m.status,
            f.nome AS funcionario
        FROM manutencoes m
        INNER JOIN funcionarios f
            ON f.id = m.funcionario_id
        ORDER BY m.data_registro DESC, m.id DESC';

$manutencoes = $pdo->query($sql)->fetchAll();

$tituloPagina = 'Manutenções - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Manutenções</h1>

<?php if (count($manutencoes) === 0): ?>
    <p>Nenhuma ocorrência de manutenção registrada.</p>
<?php else: ?>
    <?php foreach ($manutencoes as $manutencao): ?>
        <section>
            <h2>
                Ocorrência #<?= (int) $manutencao['id'] ?>
                — <?= htmlspecialchars(
                    $manutencao['equipamento'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h2>

            <dl>
                <dt>Problema</dt>
                <dd>
                    <?= nl2br(htmlspecialchars(
                        $manutencao['descricao_problema'],
                        ENT_QUOTES,
                        'UTF-8'
                    )) ?>
                </dd>

                <dt>Data do registro</dt>
                <dd>
                    <?= htmlspecialchars(
                        $manutencao['data_registro'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </dd>

                <dt>Funcionário responsável</dt>
                <dd>
                    <?= htmlspecialchars(
                        $manutencao['funcionario'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </dd>

                <dt>Status</dt>
                <dd>
                    <?= htmlspecialchars(
                        $manutencao['status'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </dd>
            </dl>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>