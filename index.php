<?php
// READ - Lista todos os registros (com busca opcional)
require 'conexao.php';

$busca = trim($_GET['busca'] ?? '');

if ($busca !== '') {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE nome LIKE :b OR descricao LIKE :b ORDER BY id DESC");
    $stmt->execute([':b' => "%$busca%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
}
$registros = $stmt->fetchAll();

$msg  = $_GET['msg']  ?? '';
$erro = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="container">
    <header class="topo">
      <h1>Produtos</h1>
      <a href="form.php" class="btn btn-primary">+ Novo</a>
    </header>

    <?php if ($msg):  ?><div class="alerta sucesso"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="alerta erro"><?= e($erro) ?></div><?php endif; ?>

    <form method="GET" class="busca">
      <input type="text" name="busca" placeholder="Buscar por nome ou descrição..." value="<?= e($busca) ?>">
      <button type="submit" class="btn btn-secondary">Buscar</button>
      <?php if ($busca !== ''): ?><a href="index.php" class="btn btn-secondary">Limpar</a><?php endif; ?>
    </form>

    <div class="tabela-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Qtd.</th>
            <th class="acoes-col">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$registros): ?>
            <tr><td colspan="6" class="vazio">Nenhum registro encontrado.</td></tr>
          <?php endif; ?>

          <?php foreach ($registros as $r): ?>
            <tr>
              <td><?= $r['id'] ?></td>
              <td><?= e($r['nome']) ?></td>
              <td><?= e($r['descricao']) ?></td>
              <td>R$ <?= number_format($r['preco'], 2, ',', '.') ?></td>
              <td><?= $r['quantidade'] ?></td>
              <td class="acoes">
                <a href="form.php?id=<?= $r['id'] ?>" class="btn btn-small btn-edit">Editar</a>
                <form method="POST" action="excluir.php" onsubmit="return confirm('Excluir este registro?');">
                  <input type="hidden" name="id" value="<?= $r['id'] ?>">
                  <button type="submit" class="btn btn-small btn-danger">Excluir</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
