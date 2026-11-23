<?php

require_once __DIR__ . '/../includes/permissoes.php';

exigirAutenticacao();

$nomeFuncionario = $_SESSION['funcionario_nome'] ?? 'Funcionário';
$perfilFuncionario = $_SESSION['funcionario_perfil'] ?? '';

$nomesPerfis = [
    'administrador' => 'Administrador',
    'gerente' => 'Gerente',
    'atendente' => 'Atendente',
    'operador' => 'Operador',
];

$perfilLegivel = $nomesPerfis[$perfilFuncionario] ?? 'Funcionário';

$atalhos = [];

if (funcionarioPode('funcionarios.gerenciar')) {
    $atalhos[] = [
        'titulo' => 'Funcionários',
        'descricao' => 'Consulte e gerencie os acessos da equipe.',
        'link' => '/grafica-etecvav/pages/funcionarios/listar.php',
    ];
}

if (funcionarioPode('pedidos.consultar')) {
    $atalhos[] = [
        'titulo' => 'Pedidos',
        'descricao' => 'Consulte pedidos de impressão e acompanhe o andamento.',
        'link' => '/grafica-etecvav/pages/pedidos/listar.php',
    ];
}

if (funcionarioPode('vendas.consultar')) {
    $atalhos[] = [
        'titulo' => 'Vendas',
        'descricao' => 'Consulte vendas registradas e seus itens.',
        'link' => '/grafica-etecvav/pages/vendas/listar.php',
    ];
} elseif (funcionarioPode('vendas.registrar')) {
    $atalhos[] = [
        'titulo' => 'Registrar venda',
        'descricao' => 'Registre uma venda de produtos para revenda.',
        'link' => '/grafica-etecvav/pages/vendas/cadastrar.php',
    ];
}

if (funcionarioPode('produtos.consultar')) {
    $atalhos[] = [
        'titulo' => 'Produtos',
        'descricao' => 'Consulte produtos, preços e níveis de estoque.',
        'link' => '/grafica-etecvav/pages/produtos/listar.php',
    ];
}

if (
    funcionarioPode('estoque.consultar')
    || funcionarioPode('estoque.gerenciar')
) {
    $atalhos[] = [
        'titulo' => 'Estoque',
        'descricao' => 'Consulte saldos e movimentações do estoque.',
        'link' => '/grafica-etecvav/pages/estoque/consultar.php',
    ];
}

if (funcionarioPode('reservas.consultar')) {
    $atalhos[] = [
        'titulo' => 'Reservas',
        'descricao' => 'Consulte as reservas de armários.',
        'link' => '/grafica-etecvav/pages/armarios/reservas.php',
    ];
} elseif (funcionarioPode('reservas.solicitar')) {
    $atalhos[] = [
        'titulo' => 'Solicitar reserva',
        'descricao' => 'Registre uma solicitação de reserva de armário.',
        'link' => '/grafica-etecvav/pages/armarios/cadastrar_reserva.php',
    ];
}

if (
    funcionarioPode('manutencoes.consultar')
    || funcionarioPode('manutencoes.registrar')
) {
    $atalhos[] = [
        'titulo' => 'Manutenções',
        'descricao' => 'Consulte ocorrências e registre manutenções.',
        'link' => '/grafica-etecvav/pages/manutencoes/listar.php',
    ];
}

if (funcionarioPode('relatorios.consultar')) {
    $atalhos[] = [
        'titulo' => 'Relatórios',
        'descricao' => 'Consulte relatórios de pedidos e vendas.',
        'link' => '/grafica-etecvav/pages/relatorios/listar.php',
    ];
}
?>

<section class="painel-boas-vindas">
    <p class="sobretitulo">Gráfica escolar</p>

    <h1>Olá, <?= htmlspecialchars(
        $nomeFuncionario,
        ENT_QUOTES,
        'UTF-8'
    ) ?>.</h1>

    <p class="texto-destaque">
        Bem-vindo(a) ao sistema de gestão da gráfica da ETEC Vasco Antônio
        Venchiarutti.
    </p>

    <p class="identificacao-perfil">
        Perfil:
        <strong><?= htmlspecialchars(
            $perfilLegivel,
            ENT_QUOTES,
            'UTF-8'
        ) ?></strong>
    </p>
</section>

<section class="secao-atalhos" aria-labelledby="titulo-atalhos">
    <div class="cabecalho-secao">
        <div>
            <p class="sobretitulo">Acesso rápido</p>
            <h2 id="titulo-atalhos">Seus módulos</h2>
        </div>

        <p class="descricao-secao">
            Os atalhos disponíveis seguem as permissões do seu perfil.
        </p>
    </div>

    <?php if (count($atalhos) === 0): ?>
        <p>Nenhum módulo está disponível para este perfil.</p>
    <?php else: ?>
        <div class="grade-atalhos">
            <?php foreach ($atalhos as $indice => $atalho): ?>
                <a
                    class="cartao-atalho"
                    href="<?= htmlspecialchars(
                        $atalho['link'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
                    <span class="numero-atalho">
                        <?= str_pad(
                            (string) ($indice + 1),
                            2,
                            '0',
                            STR_PAD_LEFT
                        ) ?>
                    </span>

                    <h3><?= htmlspecialchars(
                        $atalho['titulo'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?></h3>

                    <p><?= htmlspecialchars(
                        $atalho['descricao'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?></p>

                    <span class="texto-abrir">Abrir módulo <span aria-hidden="true">→</span></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>