<?php
// CREATE (id = 0) e UPDATE (id > 0)
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id         = (int)($_POST['id'] ?? 0);
$nome       = trim($_POST['nome'] ?? '');
$descricao  = trim($_POST['descricao'] ?? '');
$preco      = $_POST['preco'] ?? '';
$quantidade = $_POST['quantidade'] ?? '';

// ---------- Validação no servidor ----------
$voltar = 'form.php' . ($id > 0 ? "?id=$id&" : '?');

if ($nome === '') {
    header('Location: ' . $voltar . 'erro=' . urlencode('O nome é obrigatório.'));
    exit;
}
if (!is_numeric($preco) || $preco < 0) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Preço inválido.'));
    exit;
}
if (!ctype_digit((string)$quantidade)) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Quantidade inválida.'));
    exit;
}

try {
    if ($id > 0) {
        // UPDATE
        $sql = "UPDATE produtos
                   SET nome = :nome, descricao = :descricao, preco = :preco, quantidade = :quantidade
                 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome'       => $nome,
            ':descricao'  => $descricao,
            ':preco'      => $preco,
            ':quantidade' => $quantidade,
            ':id'         => $id,
        ]);
        $msg = 'Registro atualizado com sucesso!';
    } else {
        // CREATE
        $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade)
                VALUES (:nome, :descricao, :preco, :quantidade)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome'       => $nome,
            ':descricao'  => $descricao,
            ':preco'      => $preco,
            ':quantidade' => $quantidade,
        ]);
        $msg = 'Registro cadastrado com sucesso!';
    }

    header('Location: index.php?msg=' . urlencode($msg));
} catch (PDOException $e) {
    header('Location: index.php?erro=' . urlencode('Erro ao salvar: ' . $e->getMessage()));
}
exit;
