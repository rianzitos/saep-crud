<?php
// DELETE
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php?erro=' . urlencode('ID inválido.'));
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header('Location: index.php?msg=' . urlencode('Registro excluído com sucesso!'));
} catch (PDOException $e) {
    header('Location: index.php?erro=' . urlencode('Erro ao excluir: ' . $e->getMessage()));
}
exit;
