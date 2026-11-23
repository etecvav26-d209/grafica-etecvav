<?php

require_once __DIR__ . '/includes/permissoes.php';

exigirAutenticacao();

$tituloPagina = 'Início - Gráfica ETEC VAV';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/pages/inicio.php';
require_once __DIR__ . '/includes/footer.php';