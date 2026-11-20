<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('funcionarios.gerenciar');

require_once __DIR__ . '/../../config/conexao.php';

$sql = 'SELECT id, nome, usuario, perfil, ativo, data_cadastro
        FROM funcionarios
        ORDER BY nome ASC';

$funcionarios = $pdo->query($sql)->fetchAll();

$tituloPagina = 'Funcionários - Gráfica ETEC VAV';
require_once __DIR__ . '/../../includes/header.php';
?>

<h1>Funcionários</h1>

<p><a href="cadastrar.php">Cadastrar funcionário</a></p>

<?php if (count($funcionarios) === 0): ?>
    <p>Nenhum funcionário cadastrado.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Usuário</th>
                <th scope="col">Perfil</th>
                <th scope="col">Situação</th>
                <th scope="col">Data de cadastro</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($funcionarios as $funcionario): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars(
                            $funcionario['nome'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $funcionario['usuario'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $funcionario['perfil'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <?= (int) $funcionario['ativo'] === 1
                            ? 'Ativo'
                            : 'Inativo' ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(
                            $funcionario['data_cadastro'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <a href="editar.php?id=<?= (int) $funcionario['id'] ?>">
                            Editar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
