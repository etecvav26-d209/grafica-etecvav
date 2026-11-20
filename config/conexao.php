<?php

$host = 'localhost';
$banco = 'grafica';
$usuario = 'root';
$senha = '';

$dsn = "mysql:host={$host};dbname={$banco};charset=utf8mb4";

$opcoes = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $usuario, $senha, $opcoes);
} catch (PDOException $erro) {
    exit('Não foi possível conectar ao banco de dados.');
}
