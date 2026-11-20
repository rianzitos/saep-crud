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
//        os 3 "$r['id']" da tabela. (O name="id" do form e o ?id= da URL podem ficar.)
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
  $stmt = $pdo->prepare("SELECT * FROM alunos WHERE nome LIKE :b OR email LIKE :b OR curso LIKE :b ORDER BY nome ASC");
  $stmt->execute([':b' => "%$busca%"]);
} else {
  // >>> ADAPTAR (1): aqui também troque "produtos" (e o ORDER BY, se mudou acima).
  $stmt = $pdo->query("SELECT * FROM alunos ORDER BY nome ASC");
}
$registros = $stmt->fetchAll();

// >>> [ALERTA-ESTOQUE] BLOCO 1 de 3 (PHP) — INÍCIO (para remover: apague desta linha até o marcador final do bloco) <<<
// Descobre quais itens estão EM FALTA (quantidade 0) ou com ESTOQUE BAIXO.
// O alerta olha a tabela inteira, mesmo quando você está usando a busca.
//
// >>> COMO REMOVER (se a prova NÃO pedir alerta de estoque):
//     • Jeito rápido: troque  true  por  false  na linha $ALERTA_ESTOQUE_ATIVO.
//     • Jeito limpo : Ctrl+F "ALERTA-ESTOQUE" e apague os 3 blocos deste arquivo, cada um da
//                     linha "INÍCIO" até a linha "FIM" (inclusive):
//                     1 = este PHP, 2 = faixas de aviso no topo, 3 = selo na coluna Qtd.
//                     (Opcional: apague também o bloco [ALERTA-ESTOQUE] do style.css.)
//
// >>> ADAPTAR (se a prova pedir o alerta, mas com outra regra):
//     • Limite: mude $LIMITE_ESTOQUE_BAIXO. A regra é "menor OU IGUAL".
//               Ex.: a prova diz "menos de 10 unidades" → use 9.
//     • Coluna: se a coluna do estoque não for "quantidade" (ex.: "estoque"),
//               troque "quantidade" no SQL abaixo e no BLOCO 3 ($r['quantidade']).
//     • Tabela e nome: troque "produtos" e "nome" se mudaram (ex.: livros / titulo).
// ---------------------------------------------------------------------
$ALERTA_ESTOQUE_ATIVO = true;   // (nome antigo da chave; pode manter) true = mostra os alertas | false = desliga

$emFalta = [];        // aqui: alunos REPROVADOS (média abaixo de 4)
$estoqueBaixo = [];   // aqui: alunos em RECUPERAÇÃO (média de 4 até menos de 6)

if ($ALERTA_ESTOQUE_ATIVO) {
  // a média NÃO existe no banco: o próprio SQL calcula (nota1 + nota2 + nota3) / 3
  $stmtAlerta = $pdo->query(
    "SELECT nome, (nota1 + nota2 + nota3) / 3 AS media FROM alunos WHERE (nota1 + nota2 + nota3) / 3 < 6 ORDER BY media, nome"
  );

  foreach ($stmtAlerta->fetchAll() as $item) {
    $txt = $item['nome'] . ' (' . number_format($item['media'], 1, ',', '.') . ')';
    if ($item['media'] < 4) {
      $emFalta[] = $txt;
    } else {
      $estoqueBaixo[] = $txt;
    }
  }
}
// >>> [ALERTA-ESTOQUE] BLOCO 1 de 3 (PHP) — FIM <<<

$msg = $_GET['msg'] ?? '';
$erro = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php /* >>> ADAPTAR (3): troque o título da aba do navegador. Ex.: <title>Livros</title> */ ?>
  <title>Alunos</title>
  <?php /* >>> VISUAL: esta página usa SÓ o style.css (pasta principal).
             Para trocar o visual, troque o CONTEÚDO do style.css por um dos arquivos da pasta temas/
             (instruções no começo do style.css). NÃO precisa mexer nesta linha. */ ?>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <main class="container">
    <header class="topo">
      <?php /* >>> ADAPTAR (3): troque o título da página. Ex.: <h1>Livros</h1> (e "+ Novo" se quiser) */ ?>
      <h1>Alunos</h1>
      <a href="form.php" class="btn btn-primary">+ Novo</a>
    </header>

    <?php if ($msg): ?>
      <div class="alerta sucesso"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($erro): ?>
      <div class="alerta erro"><?= e($erro) ?></div><?php endif; ?>

    <?php /* >>> [ALERTA-ESTOQUE] BLOCO 2 de 3 (faixas de aviso) — INÍCIO (para remover: apague desta linha até o marcador final do bloco) <<< */ ?>
    <?php
    /* Só aparecem se houver item em falta / com estoque baixo.
       >>> ADAPTAR: troque os textos "Em falta" / "Estoque baixo" se a prova usar outras palavras.
       >>> REMOVER: apague do INÍCIO ao FIM (se usar só o jeito rápido, pode deixar). */
    ?>
    <?php if ($emFalta): ?>
      <div class="alerta erro">
        <strong>⚠ Reprovados (<?= count($emFalta) ?>):</strong>
        <?= e(implode(', ', $emFalta)) ?>
      </div>
    <?php endif; ?>
    <?php if ($estoqueBaixo): ?>
      <div class="alerta aviso">
        <strong>⚠ Em recuperação (<?= count($estoqueBaixo) ?>):</strong>
        <?= e(implode(', ', $estoqueBaixo)) ?>
      </div>
    <?php endif; ?>
    <?php /* >>> [ALERTA-ESTOQUE] BLOCO 2 de 3 (faixas de aviso) — FIM <<< */ ?>

    <form method="GET" class="busca">
      <?php /* >>> ADAPTAR: troque o placeholder. Ex.: "Buscar por título ou autor..." */ ?>
      <input type="text" name="busca" placeholder="Buscar por nome, e-mail ou curso..." value="<?= e($busca) ?>">
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
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Curso</th>
            <th>N1</th>
            <th>N2</th>
            <th>N3</th>
            <th>Média</th>
            <th>Situação</th>
            <th class="acoes-col">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php /* >>> ADAPTAR (6): colspan = número total de <th> acima. Hoje são 6; com 7 colunas, use 7. */ ?>
          <?php if (!$registros): ?>
            <tr>
              <td colspan="10" class="vazio">Nenhum registro encontrado.</td>
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
               • Chave primária com outro nome? Troque $r['id'] (aqui, no Editar e no Excluir). */
            ?>
            <tr>
              <?php $media = ($r['nota1'] + $r['nota2'] + $r['nota3']) / 3; ?>
              <td><?= $r['id'] ?></td>
              <td><?= e($r['nome']) ?></td>
              <td><?= e($r['email']) ?></td>
              <td><?= e($r['curso']) ?></td>
              <td><?= number_format($r['nota1'], 1, ',', '.') ?></td>
              <td><?= number_format($r['nota2'], 1, ',', '.') ?></td>
              <td><?= number_format($r['nota3'], 1, ',', '.') ?></td>
              <td><strong><?= number_format($media, 1, ',', '.') ?></strong></td>
              <td>
                <?php /* >>> [ALERTA-ESTOQUE] BLOCO 3 de 3 (selo na coluna Qtd.) — INÍCIO (para remover: apague desta linha até o marcador final do bloco) <<< */ ?>
                <?php if ($media >= 6): ?>
                  Aprovado
                <?php elseif ($media >= 4): ?>
                  <span class="selo selo-baixo">Recuperação</span>
                <?php else: ?>
                  <span class="selo selo-falta">Reprovado</span>
                <?php endif; ?>
                <?php /* >>> [ALERTA-ESTOQUE] BLOCO 3 de 3 (selo na coluna Qtd.) — FIM <<< */ ?>
              </td>
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