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
$registro = ['paciente' => '', 'especialidade' => '', 'convenio' => 'Particular', 'data_consulta' => '', 'horario' => '', 'valor' => '', 'status' => 'Agendada', 'observacoes' => ''];

// Se veio id, carrega o registro para edição
if ($id > 0) {
    // >>> ADAPTAR (1): troque "produtos" pela tabela. Chave primária não é "id"? Troque "WHERE id".
    //     Ex.: SELECT * FROM livros WHERE codigo = :id   (o :id é só o nome do parâmetro, pode ficar)
    $stmt = $pdo->prepare("SELECT * FROM consultas WHERE codigo = :id");
    $stmt->execute([':id' => $id]);
    $encontrado = $stmt->fetch();

    if (!$encontrado) {
        header('Location: index.php?erro=' . urlencode('Registro não encontrado.'));
        exit;
    }
    $registro = $encontrado;
}

// >>> ADAPTAR (3): títulos da página. Ex.: 'Editar Livro' / 'Novo Livro'
$titulo = $id > 0 ? 'Editar Consulta' : 'Novo Consulta';
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
        <label for="paciente">Paciente *</label>
        <input type="text" id="paciente" name="paciente" maxlength="100" required value="<?= e($registro['paciente']) ?>">
      </div>

      <div class="linha">
        <div class="campo">
          <label for="especialidade">Especialidade *</label>
          <select id="especialidade" name="especialidade" required>
            <option value="">Selecione...</option>
            <option value="Clínico Geral" <?= $registro['especialidade'] === 'Clínico Geral' ? 'selected' : '' ?>>Clínico Geral</option>
            <option value="Cardiologia" <?= $registro['especialidade'] === 'Cardiologia' ? 'selected' : '' ?>>Cardiologia</option>
            <option value="Dermatologia" <?= $registro['especialidade'] === 'Dermatologia' ? 'selected' : '' ?>>Dermatologia</option>
            <option value="Pediatria" <?= $registro['especialidade'] === 'Pediatria' ? 'selected' : '' ?>>Pediatria</option>
          </select>
        </div>

        <div class="campo">
          <label for="convenio">Convênio *</label>
          <select id="convenio" name="convenio" required>
            <option value="">Selecione...</option>
            <option value="Particular" <?= $registro['convenio'] === 'Particular' ? 'selected' : '' ?>>Particular</option>
            <option value="Unimed" <?= $registro['convenio'] === 'Unimed' ? 'selected' : '' ?>>Unimed</option>
            <option value="Bradesco Saúde" <?= $registro['convenio'] === 'Bradesco Saúde' ? 'selected' : '' ?>>Bradesco Saúde</option>
            <option value="SUS" <?= $registro['convenio'] === 'SUS' ? 'selected' : '' ?>>SUS</option>
          </select>
        </div>
      </div>

      <div class="linha">
        <div class="campo">
          <label for="data_consulta">Data da consulta *</label>
          <input type="date" id="data_consulta" name="data_consulta" required value="<?= e($registro['data_consulta']) ?>">
        </div>

        <div class="campo">
          <label for="horario">Horário *</label>
          <input type="time" id="horario" name="horario" required value="<?= e(substr($registro['horario'], 0, 5)) ?>">
        </div>
      </div>

      <div class="linha">
        <div class="campo">
          <label for="valor">Valor (R$) *</label>
          <input type="number" id="valor" name="valor" step="0.01" min="0" required value="<?= e($registro['valor']) ?>">
        </div>

        <div class="campo">
          <label for="status">Status *</label>
          <select id="status" name="status" required>
            <option value="">Selecione...</option>
            <option value="Agendada" <?= $registro['status'] === 'Agendada' ? 'selected' : '' ?>>Agendada</option>
            <option value="Realizada" <?= $registro['status'] === 'Realizada' ? 'selected' : '' ?>>Realizada</option>
            <option value="Cancelada" <?= $registro['status'] === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
          </select>
        </div>
      </div>

      <div class="campo">
        <label for="observacoes">Observações</label>
        <textarea id="observacoes" name="observacoes" rows="3"><?= e($registro['observacoes']) ?></textarea>
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
