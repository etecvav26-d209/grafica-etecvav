<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('funcionarios.gerenciar');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Método não permitido.');
}

$idRecebido = $_POST['id'] ?? null;

$id = filter_var(
    $idRecebido,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if ($id === false) {
    http_response_code(400);
    exit('Identificador inválido.');
}

require_once __DIR__ . '/../../config/conexao.php';

$sql = 'UPDATE funcionarios
        SET ativo = 0
        WHERE id = :id AND ativo = 1';

$consulta = $pdo->prepare($sql);
$consulta->execute(['id' => $id]);

header('Location: listar.php');
exit;
