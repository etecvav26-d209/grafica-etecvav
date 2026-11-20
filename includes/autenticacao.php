<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function funcionarioAutenticado(): bool
{
    return isset($_SESSION['funcionario_id']);
}

function exigirAutenticacao(): void
{
    if (!funcionarioAutenticado()) {
        header('Location: /grafica-etecvav/login.php');
        exit;
    }
}
