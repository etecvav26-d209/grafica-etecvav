<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('reservas.consultar');

require_once __DIR__ . '/../../config/conexao.php';

$sql = 'SELECT
            r.id,
            r.estudante,
            r.sala,
            r.curso,
            a.numero AS numero_armario,
            a.tamanho AS tamanho_armario,
            r.ano_letivo,
            r.data_reserva,
            r.situacao_pgmento,
            f.nome AS funcionario
        FROM reservas_armario r
        INNER JOIN armario a
            ON a.id = r.armario_id
        INNER JOIN funcionarios f
            ON f.id = r.funcionario_id
        ORDER BY r.ano_letivo DESC, r.data_reserva DESC, r.id DESC';

$reservas = $pdo->query($sql)->fetchAll();

$tituloPagina = 'Reservas de armários - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Reservas de armários</h1>

<?php if (funcionarioPode('reservas.solicitar')): ?>
    <p>
        <a href="cadastrar_reserva.php">Solicitar reserva</a>
    </p>
<?php endif; ?>

<?php if (funcionarioPode('reservas.gerenciar')): ?>
    <p>
        <a href="listar.php">Consultar armários</a>
    </p>
<?php endif; ?>

<?php if (count($reservas) === 0): ?>
    <p>Nenhuma reserva cadastrada.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Estudante</th>
                <th>Sala</th>
                <th>Curso</th>
                <th>Armário</th>
                <th>Ano letivo</th>
                <th>Data da reserva</th>
                <th>Situação do pagamento</th>
                <th>Funcionário responsável</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservas as $reserva): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars(
                            $reserva['estudante'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $reserva['sala'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $reserva['curso'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $reserva['numero_armario'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                        (<?= htmlspecialchars(
                            $reserva['tamanho_armario'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>)
                    </td>
                    <td><?= (int) $reserva['ano_letivo'] ?></td>
                    <td>
                        <?= htmlspecialchars(
                            $reserva['data_reserva'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $reserva['situacao_pgmento'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $reserva['funcionario'],
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