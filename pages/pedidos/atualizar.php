<?php

require_once __DIR__ . '/../../includes/permissoes.php';

exigirPermissao('pedidos.atualizar_status');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido.');
}

require_once __DIR__ . '/../../config/conexao.php';

$pedidoIdRecebido = $_POST['pedido_id'] ?? '';
$statusRecebido = $_POST['status'] ?? '';

$pedidoId = is_string($pedidoIdRecebido)
    ? filter_var($pedidoIdRecebido, FILTER_VALIDATE_INT)
    : false;

$status = is_string($statusRecebido)
    ? trim($statusRecebido)
    : '';

$statusPermitidos = [
    'em produção',
    'pronto',
    'entregue',
];

if ($pedidoId === false || $pedidoId < 1) {
    http_response_code(400);
    exit('Pedido inválido.');
}

if (!in_array($status, $statusPermitidos, true)) {
    http_response_code(400);
    exit('Status inválido.');
}

$sqlAtualizacao = 'UPDATE pedidos
                   SET status = :status
                   WHERE id = :id';

$consultaAtualizacao = $pdo->prepare($sqlAtualizacao);
$consultaAtualizacao->execute([
    'status' => $status,
    'id' => $pedidoId,
]);

if ($consultaAtualizacao->rowCount() === 0) {
    $sqlVerificaPedido = 'SELECT id
                          FROM pedidos
                          WHERE id = :id';

    $consultaVerificaPedido = $pdo->prepare($sqlVerificaPedido);
    $consultaVerificaPedido->execute(['id' => $pedidoId]);

    if (!$consultaVerificaPedido->fetch()) {
        http_response_code(404);
        exit('Pedido não encontrado.');
    }
}

$_SESSION['mensagem_status_pedido'] = 'Status do pedido atualizado.';

header('Location: listar.php');
exit;