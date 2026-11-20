<?php
// =====================================================================
// Formulário usado para CREATE (sem id) e UPDATE (com id)
// =====================================================================
// >>> ADAPTAR — O QUE MUDAR NESTE ARQUIVO SE A PROVA PEDIR OUTRA COISA
//     Dica: Ctrl+F por "ADAPTAR" mostra cada ponto a mudar (numerados 1, 2, 3...).
//
//     Exemplo usado nos comentários — a prova pede "LIVROS" em vez de "PRODUTOS":
//         tabela  produtos → livros   |   nome → titulo   |   descricao → autor
//         preco → preco (igual)       |   quantidade → estoque
//
//     1) Nome da tabela no SELECT (modo editar)
//     2) Array $registro: uma chave para CADA campo do formulário
//     3) $titulo da página ("Editar Produto" / "Novo Produto")
//     4) Os campos <input>: um bloco <div class="campo"> para cada coluna
//     5) Visual (cores/tema): NÃO é aqui. Troque o conteúdo do style.css (veja o começo dele).
//
//     REGRA IMPORTANTE: o name="..." de cada <input> aqui tem que ser IGUAL ao
//     $_POST['...'] lido no salvar.php, e o nome em $registro['...'] tem que ser
//     IGUAL ao nome da coluna no banco.
// =====================================================================
require 'conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// >>> ADAPTAR (2): valores padrão do formulário vazio (modo "Novo").
//     Uma chave para cada campo, com o MESMO NOME da coluna do banco (no modo "editar",
//     este array é substituído pela linha vinda do banco: $registro = $encontrado).
//     Ex. livros:    $registro = ['titulo' => '', 'autor' => '', 'preco' => '', 'estoque' => ''];
//     Campo novo "categoria"?  Acrescente  'categoria' => ''  na lista.
$registro = ['nome' => '', 'email' => '', 'telefone' => '', 'curso' => '', 'data_nascimento' => '', 'nota1' => '', 'nota2' => '', 'nota3' => '0'];

// Se veio id, carrega o registro para edição
if ($id > 0) {
    // >>> ADAPTAR (1): troque "produtos" pela tabela. Chave primária não é "id"? Troque "WHERE id".
    //     Ex.: SELECT * FROM livros WHERE codigo = :id   (o :id é só o nome do parâmetro, pode ficar)
    $stmt = $pdo->prepare("SELECT * FROM alunos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $encontrado = $stmt->fetch();

    if (!$encontrado) {
        header('Location: index.php?erro=' . urlencode('Registro não encontrado.'));
        exit;
    }
    $registro = $encontrado;
}

// >>> ADAPTAR (3): títulos da página. Ex.: 'Editar Livro' / 'Novo Livro'
$titulo = $id > 0 ? 'Editar Aluno' : 'Novo Aluno';
$erro   = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($titulo) ?></title>
  <?php /* >>> VISUAL: esta página usa SÓ o style.css (pasta principal).
             Para trocar o visual, troque o CONTEÚDO do style.css por um dos arquivos da pasta temas/
             (instruções no começo do style.css). NÃO precisa mexer nesta linha. */ ?>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="container container-form">
    <header class="topo">
      <h1><?= e($titulo) ?></h1>
    </header>

    <?php if ($erro): ?><div class="alerta erro"><?= e($erro) ?></div><?php endif; ?>

    <form method="POST" action="salvar.php" class="form">
      <?php /* NÃO REMOVER: é este campo que diz ao salvar.php se é CREATE (id = 0) ou UPDATE (id maior que 0). */ ?>
      <input type="hidden" name="id" value="<?= $id ?>">

      <?php
      /* >>> ADAPTAR (4): um bloco <div class="campo"> para cada coluna editável.
         Cada bloco tem 3 partes que precisam combinar:
            <label for="X">   texto que o usuário vê (o "for" = id do input)
            <input id="X" name="X" ...>   o "name" é o nome que chega no salvar.php ($_POST['X'])
            value="<?= e($registro['X']) ?>"   preenche o campo ao editar (X = nome da coluna)
         • "required" = obrigatório no navegador (a validação de verdade fica no salvar.php).
         • "maxlength" = tamanho máximo (combine com o VARCHAR(n) do banco).

         Ex. livros — troque o bloco "Nome" por:
            <label for="titulo">Título *</label>
            <input type="text" id="titulo" name="titulo" maxlength="100" required value="<?= e($registro['titulo']) ?>">
         • ADICIONAR campo: copie um bloco <div class="campo"> inteiro e mude id, name, label e $registro['...'].
           (Lembre de acrescentar a chave no array $registro lá em cima e tratar o campo no salvar.php.)
         • REMOVER campo: apague o bloco <div class="campo"> e a chave do array $registro. */
      ?>
      <div class="campo">
        <label for="nome">Nome *</label>
        <input type="text" id="nome" name="nome" maxlength="100" required value="<?= e($registro['nome']) ?>">
      </div>

      <div class="linha">
        <div class="campo">
          <label for="email">E-mail *</label>
          <input type="email" id="email" name="email" maxlength="120" required value="<?= e($registro['email']) ?>">
        </div>

        <div class="campo">
          <label for="telefone">Telefone</label>
          <input type="tel" id="telefone" name="telefone" maxlength="15" placeholder="(11) 98888-7777" value="<?= e($registro['telefone']) ?>">
        </div>
      </div>

      <div class="linha">
        <div class="campo">
          <label for="curso">Curso *</label>
          <select id="curso" name="curso" required>
            <option value="">Selecione...</option>
            <option value="Informática" <?= $registro['curso'] === 'Informática' ? 'selected' : '' ?>>Informática</option>
            <option value="Eletrônica" <?= $registro['curso'] === 'Eletrônica' ? 'selected' : '' ?>>Eletrônica</option>
            <option value="Mecânica" <?= $registro['curso'] === 'Mecânica' ? 'selected' : '' ?>>Mecânica</option>
            <option value="Administração" <?= $registro['curso'] === 'Administração' ? 'selected' : '' ?>>Administração</option>
          </select>
        </div>

        <div class="campo">
          <label for="data_nascimento">Data de nascimento</label>
          <input type="date" id="data_nascimento" name="data_nascimento" value="<?= e($registro['data_nascimento']) ?>">
        </div>
      </div>

      <div class="linha">
        <div class="campo">
          <label for="nota1">Nota 1 *</label>
          <input type="number" id="nota1" name="nota1" step="0.1" min="0" max="10" required value="<?= e($registro['nota1']) ?>">
        </div>

        <div class="campo">
          <label for="nota2">Nota 2 *</label>
          <input type="number" id="nota2" name="nota2" step="0.1" min="0" max="10" required value="<?= e($registro['nota2']) ?>">
        </div>

        <div class="campo">
          <label for="nota3">Nota 3 *</label>
          <input type="number" id="nota3" name="nota3" step="0.1" min="0" max="10" required value="<?= e($registro['nota3']) ?>">
        </div>
      </div>

      <?php
      /* >>> ADAPTAR — OUTROS TIPOS DE CAMPO (copie para dentro de um <div class="campo">):

           Texto longo:     <textarea id="descricao" name="descricao" rows="3"><?= e($registro['descricao']) ?></textarea>
           Data:            <input type="date" id="data" name="data" value="<?= e($registro['data']) ?>">
           E-mail:          <input type="email" id="email" name="email" required value="<?= e($registro['email']) ?>">
           Inteiro:         <input type="number" min="0" ...>
           Decimal:         <input type="number" step="0.01" min="0" ...>
           Lista suspensa (a opção salva já vem marcada ao editar):
             <select id="categoria" name="categoria" required>
               <option value="">Selecione...</option>
               <option value="Periféricos" <?= $registro['categoria'] === 'Periféricos' ? 'selected' : '' ?>>Periféricos</option>
               <option value="Monitores"   <?= $registro['categoria'] === 'Monitores'   ? 'selected' : '' ?>>Monitores</option>
             </select>
         (Esses exemplos são só referência: estão dentro de um comentário e não aparecem na tela.) */
      ?>

      <div class="form-botoes">
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
  </main>
</body>
</html>
