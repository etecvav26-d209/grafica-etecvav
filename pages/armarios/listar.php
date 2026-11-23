<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('reservas.consultar');

require_once __DIR__ . '/../../config/conexao.php';

$sql = 'SELECT id, numero, tamanho
        FROM armario
        ORDER BY numero ASC';

$armarios = $pdo->query($sql)->fetchAll();

$tituloPagina = 'Armários - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Armários</h1>

<?php if (funcionarioPode('reservas.gerenciar')): ?>
    <p>
        <a href="cadastrar_armario.php">Cadastrar armário</a>
    </p>
<?php endif; ?>

<?php if (count($armarios) === 0): ?>
    <p>Nenhum armário cadastrado.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Número</th>
                <th>Tamanho</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($armarios as $armario): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars(
                            $armario['numero'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $armario['tamanho'],
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