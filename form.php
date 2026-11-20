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

// >>> ADAPTAR — LISTA SUSPENSA COM OPÇÕES QUE VÊM DO BANCO (ex.: a CATEGORIA do produto)
//     Se a prova tem uma tabela de categorias, carregue as opções AQUI (copie a linha abaixo, tirando as barras)
//     e use no <select> mais embaixo (veja "Lista suspensa com opções do BANCO" no comentário
//     "OUTROS TIPOS DE CAMPO"). Se as opções são poucas e fixas, NÃO precisa disso.
//         $categorias = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll();

// >>> ADAPTAR (2): valores padrão do formulário vazio (modo "Novo").
//     Uma chave para cada campo, com o MESMO NOME da coluna do banco (no modo "editar",
//     este array é substituído pela linha vinda do banco: $registro = $encontrado).
//     Ex. livros:    $registro = ['titulo' => '', 'autor' => '', 'preco' => '', 'estoque' => ''];
//     Campo novo "categoria"?  Acrescente  'categoria' => ''  na lista.
//     Caixa de marcar (sim/não)?  Acrescente  'ativo' => '0'  (começa desmarcada).
//     Categoria que vem de OUTRA tabela?  Acrescente  'categoria_id' => ''  (a coluna guarda só o número).
$registro = ['nome' => '', 'descricao' => '', 'preco' => '', 'quantidade' => ''];

// Se veio id, carrega o registro para edição
if ($id > 0) {
    // >>> ADAPTAR (1): troque "produtos" pela tabela. Chave primária não é "id"? Troque "WHERE id".
    //     Ex.: SELECT * FROM livros WHERE codigo = :id   (o :id é só o nome do parâmetro, pode ficar)
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $encontrado = $stmt->fetch();

    if (!$encontrado) {
        header('Location: index.php?erro=' . urlencode('Registro não encontrado.'));
        exit;
    }
    $registro = $encontrado;
}

// >>> ADAPTAR (3): títulos da página. Ex.: 'Editar Livro' / 'Novo Livro'
$titulo = $id > 0 ? 'Editar Produto' : 'Novo Produto';
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

      <div class="campo">
        <label for="descricao">Descrição</label>
        <input type="text" id="descricao" name="descricao" maxlength="255" value="<?= e($registro['descricao']) ?>">
      </div>

      <?php /* Dois campos lado a lado: ficam dentro de <div class="linha">. Para campo sozinho, use só <div class="campo">. */ ?>
      <div class="linha">
        <div class="campo">
          <label for="preco">Preço (R$) *</label>
          <input type="number" id="preco" name="preco" step="0.01" min="0" required value="<?= e($registro['preco']) ?>">
        </div>

        <div class="campo">
          <?php /* >>> ADAPTAR: se a coluna se chamar "estoque", troque id, name, label e $registro['estoque']. */ ?>
          <label for="quantidade">Quantidade *</label>
          <input type="number" id="quantidade" name="quantidade" min="0" required value="<?= e($registro['quantidade']) ?>">
        </div>
      </div>

      <?php
      /* >>> ADAPTAR — OUTROS TIPOS DE CAMPO (copie para dentro de um <div class="campo">):

           Texto longo:     <textarea id="descricao" name="descricao" rows="3"><?= e($registro['descricao']) ?></textarea>
           Data:            <input type="date" id="data" name="data" value="<?= e($registro['data']) ?>">
           E-mail:          <input type="email" id="email" name="email" required value="<?= e($registro['email']) ?>">
           Inteiro:         <input type="number" min="0" ...>
           Decimal:         <input type="number" step="0.01" min="0" ...>
           QUAL TIPO USAR? (escolha pelo que o enunciado diz)
             • sim/não (ativo, emprestado, em promoção) .... CAIXA DE MARCAR   → coluna TINYINT(1)
             • poucas opções fixas (status, turno) ......... LISTA SUSPENSA    → coluna VARCHAR com o texto
             • escolher UMA opção, todas visíveis .......... BOTÕES DE OPÇÃO   → coluna VARCHAR com o texto
             • categoria/curso CADASTRADO em outra tabela .. LISTA DO BANCO    → coluna INT (categoria_id)

           1) Lista suspensa com opções FIXAS (a opção salva já vem marcada ao editar):
             <select id="categoria" name="categoria" required>
               <option value="">Selecione...</option>
               <option value="Periféricos" <?= $registro['categoria'] === 'Periféricos' ? 'selected' : '' ?>>Periféricos</option>
               <option value="Monitores"   <?= $registro['categoria'] === 'Monitores'   ? 'selected' : '' ?>>Monitores</option>
             </select>
             (no salvar.php:  $categoria = trim($_POST['categoria'] ?? '');  e confira com in_array)

           2) Caixa de marcar, sim/não (ex.: "ativo"). A coluna é TINYINT(1): 1 = marcado, 0 = desmarcado:
             <label>
               <input type="checkbox" name="ativo" value="1" <?= $registro['ativo'] ? 'checked' : '' ?>>
               Produto ativo
             </label>
             (no salvar.php:  $ativo = isset($_POST['ativo']) ? 1 : 0;
              caixa DESMARCADA não envia nada ao servidor, por isso o isset: se chegou, está marcada.)

           3) Botões de opção, escolhe UMA (ex.: "tipo"). A opção salva já vem marcada ao editar:
             <label><input type="radio" name="tipo" value="Novo"  <?= $registro['tipo'] === 'Novo'  ? 'checked' : '' ?>> Novo</label>
             <label><input type="radio" name="tipo" value="Usado" <?= $registro['tipo'] === 'Usado' ? 'checked' : '' ?>> Usado</label>
             (no salvar.php:  $tipo = trim($_POST['tipo'] ?? '');  nenhum marcado = vazio: valide se for obrigatório.)

           4) Lista suspensa com opções do BANCO (ex.: categoria que está na tabela "categorias").
              O produto guarda só o NÚMERO da categoria, na coluna categoria_id.
              Precisa da linha $categorias = ... que está lá em CIMA, no começo deste arquivo:
             <select id="categoria_id" name="categoria_id" required>
               <option value="">Selecione...</option>
               <?php foreach ($categorias as $c): ?>
                 <option value="<?= $c['id'] ?>" <?= $c['id'] == $registro['categoria_id'] ? 'selected' : '' ?>><?= e($c['nome']) ?></option>
               <?php endforeach; ?>
             </select>
             (no salvar.php:  $categoria_id = (int)($_POST['categoria_id'] ?? 0);  e confira se esse ID existe.)
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
