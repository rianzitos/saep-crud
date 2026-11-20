<?php
// =====================================================================
// DELETE
// Recebe o id enviado pelo botão "Excluir" do index.php e apaga o registro.
// =====================================================================
// >>> ADAPTAR — O QUE MUDAR NESTE ARQUIVO SE A PROVA PEDIR OUTRA COISA
//     Dica: Ctrl+F por "ADAPTAR" mostra cada ponto a mudar.
//     1) Nome da tabela no DELETE        2) Chave primária no WHERE (se não for "id")
//     3) Mensagem de sucesso
//     Só isso! Este é o arquivo mais simples de adaptar.
// =====================================================================
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
    // >>> ADAPTAR (1 e 2): troque "produtos" pela tabela e "id" pela chave primária, se for outra.
    //     Ex.: DELETE FROM livros WHERE codigo = :id   (o :id é só o nome do parâmetro, pode ficar)
    $stmt = $pdo->prepare("DELETE FROM alunos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    // >>> ADAPTAR (3): texto de sucesso. Ex.: 'Livro excluído com sucesso!'
    header('Location: index.php?msg=' . urlencode('Aluno excluído com sucesso!'));
} catch (PDOException $e) {
    // Cai aqui, por exemplo, se o registro estiver ligado a outra tabela (chave estrangeira).
    header('Location: index.php?erro=' . urlencode('Erro ao excluir: ' . $e->getMessage()));
}
exit;
