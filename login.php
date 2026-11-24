<?php
// =====================================================================
// TELA DE LOGIN
// Usuário padrão (cadastrado no banco.sql):  admin  /  admin123
// A senha fica no banco como HASH (password_hash), nunca em texto puro;
// aqui conferimos com password_verify().
// =====================================================================
session_start();
require 'conexao.php';

// Já está logado? Vai direto para a lista.
if (!empty($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';
$usuario = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare("SELECT id, usuario, senha FROM usuarios WHERE usuario = :u");
    $stmt->execute([':u' => $usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        session_regenerate_id(true);   // evita fixação de sessão
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario']    = $user['usuario'];
        header('Location: index.php');
        exit;
    }

    // Mensagem genérica: não revela se foi o usuário ou a senha que errou.
    $erro = 'Usuário ou senha inválidos.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrar</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="container container-form">
    <header class="topo">
      <h1>Entrar</h1>
    </header>

    <?php if ($erro): ?><div class="alerta erro"><?= e($erro) ?></div><?php endif; ?>

    <form method="POST" class="form">
      <div class="campo">
        <label for="usuario">Usuário</label>
        <input type="text" id="usuario" name="usuario" maxlength="50" required autofocus
               autocomplete="username" value="<?= e($usuario) ?>">
      </div>

      <div class="campo">
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required autocomplete="current-password">
      </div>

      <div class="form-botoes">
        <button type="submit" class="btn btn-primary">Entrar</button>
      </div>
    </form>
  </main>
</body>
</html>
