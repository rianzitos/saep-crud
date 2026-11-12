<?php
// Formulário usado para CREATE (sem id) e UPDATE (com id)
require 'conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Valores padrão (formulário vazio)
$registro = ['nome' => '', 'descricao' => '', 'preco' => '', 'quantidade' => ''];

// Se veio id, carrega o registro para edição
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $encontrado = $stmt->fetch();

    if (!$encontrado) {
        header('Location: index.php?erro=' . urlencode('Registro não encontrado.'));
        exit;
    }
    $registro = $encontrado;
}

$titulo = $id > 0 ? 'Editar Produto' : 'Novo Produto';
$erro   = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($titulo) ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="container container-form">
    <header class="topo">
      <h1><?= e($titulo) ?></h1>
    </header>

    <?php if ($erro): ?><div class="alerta erro"><?= e($erro) ?></div><?php endif; ?>

    <form method="POST" action="salvar.php" class="form">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="campo">
        <label for="nome">Nome *</label>
        <input type="text" id="nome" name="nome" maxlength="100" required value="<?= e($registro['nome']) ?>">
      </div>

      <div class="campo">
        <label for="descricao">Descrição</label>
        <input type="text" id="descricao" name="descricao" maxlength="255" value="<?= e($registro['descricao']) ?>">
      </div>

      <div class="linha">
        <div class="campo">
          <label for="preco">Preço (R$) *</label>
          <input type="number" id="preco" name="preco" step="0.01" min="0" required value="<?= e($registro['preco']) ?>">
        </div>

        <div class="campo">
          <label for="quantidade">Quantidade *</label>
          <input type="number" id="quantidade" name="quantidade" min="0" required value="<?= e($registro['quantidade']) ?>">
        </div>
      </div>

      <div class="form-botoes">
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
  </main>
</body>
</html>
