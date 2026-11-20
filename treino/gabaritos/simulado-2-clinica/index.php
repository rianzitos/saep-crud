<?php
// =====================================================================
// READ — Lista os registros (com busca) + ALERTA DE ESTOQUE
// =====================================================================
// >>> ADAPTAR — O QUE MUDAR NESTE ARQUIVO SE A PROVA PEDIR OUTRA COISA
//     Dica: Ctrl+F por "ADAPTAR" mostra cada ponto a mudar (numerados 1, 2, 3...).
//
//     Exemplo usado nos comentários — a prova pede "LIVROS" em vez de "PRODUTOS":
//         tabela  produtos   →  livros
//         coluna  nome       →  titulo
//         coluna  descricao  →  autor
//         coluna  preco      →  preco   (igual)
//         coluna  quantidade →  estoque
//
//     1) Nome da tabela nos SELECTs (busca + lista completa)
//     2) Colunas da busca (WHERE ... LIKE)
//     3) <title> e <h1> da página
//     4) Cabeçalho da tabela: um <th> para cada coluna
//     5) Linhas da tabela: um <td> para cada coluna, na MESMA ORDEM dos <th>
//     6) colspan da linha "Nenhum registro" = total de colunas (contando "Ações")
//     7) Chave primária com outro nome (ex.: "codigo")? Troque "id" no ORDER BY e
//        os 3 "$r['codigo']" da tabela. (O name="id" do form e o ?id= da URL podem ficar.)
//     8) Alerta de estoque: blocos [ALERTA-ESTOQUE] (manter, ajustar ou remover)
//     9) Visual (cores/tema): NÃO é aqui. Troque o conteúdo do style.css (veja o começo dele).
// =====================================================================
require 'conexao.php';

$busca = trim($_GET['busca'] ?? '');

// >>> ADAPTAR (1 e 2): troque "produtos" pelo nome da tabela e as colunas do LIKE.
//     Ex. livros:  SELECT * FROM livros WHERE titulo LIKE :b OR autor LIKE :b ORDER BY id DESC
//     • Buscar em mais uma coluna? Acrescente:  OR outra_coluna LIKE :b
//     • Outra ordem? Troque o ORDER BY. Ex.: ORDER BY nome ASC (A→Z)  |  ORDER BY preco DESC
//       Dois critérios: ORDER BY data_consulta ASC, horario ASC  (primeiro a data, depois a hora)
if ($busca !== '') {
  $stmt = $pdo->prepare("SELECT * FROM consultas WHERE paciente LIKE :b OR especialidade LIKE :b ORDER BY data_consulta ASC, horario ASC");
  $stmt->execute([':b' => "%$busca%"]);
} else {
  // >>> ADAPTAR (1): aqui também troque "produtos" (e o ORDER BY, se mudou acima).
  $stmt = $pdo->query("SELECT * FROM consultas ORDER BY data_consulta ASC, horario ASC");
}
$registros = $stmt->fetchAll();


$msg = $_GET['msg'] ?? '';
$erro = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php /* >>> ADAPTAR (3): troque o título da aba do navegador. Ex.: <title>Livros</title> */ ?>
  <title>Consultas</title>
  <?php /* >>> VISUAL: esta página usa SÓ o style.css (pasta principal).
             Para trocar o visual, troque o CONTEÚDO do style.css por um dos arquivos da pasta temas/
             (instruções no começo do style.css). NÃO precisa mexer nesta linha. */ ?>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <main class="container">
    <header class="topo">
      <?php /* >>> ADAPTAR (3): troque o título da página. Ex.: <h1>Livros</h1> (e "+ Novo" se quiser) */ ?>
      <h1>Consultas</h1>
      <a href="form.php" class="btn btn-primary">+ Novo</a>
    </header>

    <?php if ($msg): ?>
      <div class="alerta sucesso"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($erro): ?>
      <div class="alerta erro"><?= e($erro) ?></div><?php endif; ?>


    <form method="GET" class="busca">
      <?php /* >>> ADAPTAR: troque o placeholder. Ex.: "Buscar por título ou autor..." */ ?>
      <input type="text" name="busca" placeholder="Buscar por paciente ou especialidade..." value="<?= e($busca) ?>">
      <button type="submit" class="btn btn-secondary">Buscar</button>
      <?php if ($busca !== ''): ?><a href="index.php" class="btn btn-secondary">Limpar</a><?php endif; ?>
    </form>

    <div class="tabela-wrap">
      <table>
        <thead>
          <?php
          /* >>> ADAPTAR (4): um <th> para cada coluna que aparece na lista.
             Ex. livros:  ID | Título | Autor | Preço | Estoque | Ações
             • ADICIONAR coluna: copie um <th>...</th> e mude o texto.
             • REMOVER coluna: apague o <th> daqui E o <td> correspondente lá embaixo. */
          ?>
          <tr>
            <th>Código</th>
            <th>Paciente</th>
            <th>Especialidade</th>
            <th>Convênio</th>
            <th>Data</th>
            <th>Horário</th>
            <th>Valor</th>
            <th>Status</th>
            <th class="acoes-col">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php /* >>> ADAPTAR (6): colspan = número total de <th> acima. Hoje são 6; com 7 colunas, use 7. */ ?>
          <?php if (!$registros): ?>
            <tr>
              <td colspan="9" class="vazio">Nenhum registro encontrado.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($registros as $r): ?>
            <?php
            /* >>> ADAPTAR (5): um <td> para cada <th>, NA MESMA ORDEM.
               Aqui dentro, $r é a linha atual do banco e $r['coluna'] é o valor da coluna.
               Formatos mais usados:
                 texto ........ <td><?= e($r['titulo']) ?></td>      (use e() em todo texto: evita XSS)
                 número ....... <td><?= $r['estoque'] ?></td>
                 dinheiro ..... <td>R$ <?= number_format($r['preco'], 2, ',', '.') ?></td>
                 data ......... <td><?= date('d/m/Y', strtotime($r['data_cadastro'])) ?></td>
                 hora ......... <td><?= substr($r['horario'], 0, 5) ?></td>        (10:30:00 vira 10:30)
                 coluna CALCULADA (não existe no banco; o PHP calcula na hora com outras colunas):
                               <td><?= number_format(($r['nota1'] + $r['nota2']) / 2, 1, ',', '.') ?></td>
                 texto conforme uma regra (ex.: situação do aluno): faça a conta ANTES do <td>, assim:
                               <?php $media = ($r['nota1'] + $r['nota2']) / 2; ?>
                               <td><?= $media >= 6 ? 'Aprovado' : 'Reprovado' ?></td>
               Ex. livros: $r['nome'] → $r['titulo'], $r['descricao'] → $r['autor'],
                           $r['quantidade'] → $r['estoque'].
               • ADICIONAR coluna: copie um <td> e troque o nome da coluna dentro de $r['...'].
               • Chave primária com outro nome? Troque $r['codigo'] (aqui, no Editar e no Excluir). */
            ?>
            <tr>
              <td><?= $r['codigo'] ?></td>
              <td><?= e($r['paciente']) ?></td>
              <td><?= e($r['especialidade']) ?></td>
              <td><?= e($r['convenio']) ?></td>
              <td><?= date('d/m/Y', strtotime($r['data_consulta'])) ?></td>
              <td><?= substr($r['horario'], 0, 5) ?></td>
              <td>R$ <?= number_format($r['valor'], 2, ',', '.') ?></td>
              <td><?= e($r['status']) ?></td>
              <td class="acoes">
                <a href="form.php?id=<?= $r['codigo'] ?>" class="btn btn-small btn-edit">Editar</a>
                <form method="POST" action="excluir.php" onsubmit="return confirm('Excluir este registro?');">
                  <input type="hidden" name="id" value="<?= $r['codigo'] ?>">
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