<?php

require_once __DIR__ . '/autenticacao.php';

$permissoesPorPerfil = [
    'administrador' => [
        'funcionarios.gerenciar',
        'pedidos.consultar',
        'pedidos.cadastrar',
        'pedidos.atualizar_status',
        'vendas.consultar',
        'vendas.registrar',
        'produtos.consultar',
        'produtos.editar',
        'estoque.consultar',
        'estoque.gerenciar',
        'reservas.consultar',
        'reservas.gerenciar',
        'reservas.solicitar',
        'manutencoes.consultar',
        'manutencoes.registrar',
        'manutencoes.atualizar',
        'relatorios.consultar',
        'relatorios.gerar',
    ],
    'gerente' => [
        'pedidos.consultar',
        'vendas.consultar',
        'produtos.consultar',
        'produtos.editar',
        'estoque.consultar',
        'reservas.consultar',
        'manutencoes.consultar',
        'relatorios.consultar',
        'relatorios.gerar',
    ],
    'atendente' => [
        'pedidos.consultar',
        'pedidos.cadastrar',
        'vendas.registrar',
        'reservas.solicitar',
    ],
    'operador' => [
        'pedidos.consultar',
        'pedidos.atualizar_status',
        'manutencoes.consultar',
        'manutencoes.registrar',
        'manutencoes.atualizar',
    ],
];

function funcionarioPode(string $permissao): bool
{
    global $permissoesPorPerfil;

    $perfil = $_SESSION['funcionario_perfil'] ?? '';

    return in_array(
        $permissao,
        $permissoesPorPerfil[$perfil] ?? [],
        true
    );
}

function exigirPermissao(string $permissao): void
{
    exigirAutenticacao();

    if (!funcionarioPode($permissao)) {
        http_response_code(403);
        exit('Acesso não autorizado.');
    }
}
