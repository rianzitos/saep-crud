<?php
// =====================================================
// CONEXÃO COM O BANCO (PDO)
// Na prova, só troque os valores abaixo.
// =====================================================
$host    = 'localhost';
$banco   = 'saep_db';
$usuario = 'root';
$senha   = '123456';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Erro ao conectar no banco: ' . $e->getMessage());
}

// Função auxiliar para escapar saída em HTML (evita XSS)
function e($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}
