<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('manutencoes.atualizar');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido.');
}

require_once __DIR__ . '/../../config/conexao.php';

$idRecebido = $_POST['manutencao_id'] ?? '';
$statusRecebido = $_POST['status'] ?? '';

$id = is_string($idRecebido)
    ? filter_var($idRecebido, FILTER_VALIDATE_INT)
    : false;

$status = is_string($statusRecebido)
    ? trim($statusRecebido)
    : '';

$statusPermitidos = [
    'em andamento',
    'concluída',
];

if ($id === false || $id < 1) {
    http_response_code(400);
    exit('Ocorrência inválida.');
}

if (!in_array($status, $statusPermitidos, true)) {
    http_response_code(400);
    exit('Status inválido.');
}

$sqlAtualizacao = 'UPDATE manutencoes
                   SET status = :status
                   WHERE id = :id';

$consultaAtualizacao = $pdo->prepare($sqlAtualizacao);
$consultaAtualizacao->execute([
    'status' => $status,
    'id' => $id,
]);

if ($consultaAtualizacao->rowCount() === 0) {
    $sqlVerifica = 'SELECT id
                    FROM manutencoes
                    WHERE id = :id';

    $consultaVerifica = $pdo->prepare($sqlVerifica);
    $consultaVerifica->execute(['id' => $id]);

    if (!$consultaVerifica->fetch()) {
        http_response_code(404);
        exit('Ocorrência não encontrada.');
    }
}

$_SESSION['mensagem_manutencao'] =
    'Andamento da manutenção atualizado.';

header('Location: listar.php');
exit;