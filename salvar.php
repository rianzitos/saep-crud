<?php
// =====================================================================
// CREATE (id = 0) e UPDATE (id > 0)
// Recebe os dados do form.php, valida e grava no banco.
// =====================================================================
// >>> ADAPTAR — O QUE MUDAR NESTE ARQUIVO SE A PROVA PEDIR OUTRA COISA
//     Dica: Ctrl+F por "ADAPTAR" mostra cada ponto a mudar (numerados 1, 2, 3...).
//
//     Exemplo usado nos comentários — a prova pede "LIVROS" em vez de "PRODUTOS":
//         tabela  produtos → livros   |   nome → titulo   |   descricao → autor
//         preco → preco (igual)       |   quantidade → estoque
//
//     1) Leitura dos campos ($_POST): uma linha por campo do formulário
//     2) Validações: uma para cada campo que precisa ser conferido
//     3) UPDATE: nome da tabela, colunas no SET e array de parâmetros
//     4) INSERT: nome da tabela, colunas, VALUES e array de parâmetros
//     5) Mensagens de sucesso
//
//     REGRA DE OURO — um CAMPO NOVO aparece em 6 lugares deste arquivo:
//         variável (1) → validação (2) → UPDATE: SET + array (3) → INSERT: colunas/VALUES + array (4)
//     e, fora daqui, no banco.sql, form.php e index.php.
//     Campo REMOVIDO: apague nesses mesmos lugares.
// =====================================================================
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// >>> ADAPTAR (1): uma linha por campo. O texto dentro de $_POST['...'] é o name="..." do <input>
//     no form.php. Ex. livros:
//         $titulo  = trim($_POST['titulo']  ?? '');     <- texto: trim() tira espaços sobrando
//         $autor   = trim($_POST['autor']   ?? '');
//         $preco   = $_POST['preco']   ?? '';           <- número: sem trim, validado logo abaixo
//         $estoque = $_POST['estoque'] ?? '';
//     • Campo opcional de DATA ou NÚMERO: se vier vazio, grave NULL em vez de '' (senão o MySQL recusa):
//         $data = ($_POST['data'] ?? '') !== '' ? $_POST['data'] : null;
$id         = (int)($_POST['id'] ?? 0);
$nome       = trim($_POST['nome'] ?? '');
$descricao  = trim($_POST['descricao'] ?? '');
$preco      = $_POST['preco'] ?? '';
$quantidade = $_POST['quantidade'] ?? '';

// ---------- Validação no servidor ----------
// Se algo estiver errado, volta para o formulário com a mensagem de erro (não grava nada).
$voltar = 'form.php' . ($id > 0 ? "?id=$id&" : '?');

// >>> ADAPTAR (2): uma validação para cada campo. Modelos prontos (troque a variável e a mensagem):
//     texto obrigatório ..... if ($titulo === '')                          → 'O título é obrigatório.'
//     tamanho mínimo ........ if (mb_strlen($nome) < 3)                    → 'Nome muito curto.'
//     número decimal ........ if (!is_numeric($preco) || $preco < 0)       → 'Preço inválido.'
//     inteiro (0 ou mais) ... if (!ctype_digit((string)$estoque))           → 'Estoque inválido.'
//     e-mail ................ if (!filter_var($email, FILTER_VALIDATE_EMAIL)) → 'E-mail inválido.'
//     opção de lista (select) if (!in_array($status, ['Agendada', 'Cancelada'], true)) → 'Status inválido.'
//                            (o usuário pode alterar o select pelo navegador: confira também no servidor)
//     nota entre 0 e 10 ..... if (!is_numeric($nota) || $nota < 0 || $nota > 10)  → 'Nota inválida.'
//     data (AAAA-MM-DD) ..... $d = DateTime::createFromFormat('Y-m-d', $data);
//                             if (!$d || $d->format('Y-m-d') !== $data)            → 'Data inválida.'
//     só dígitos (telefone) . $fone = preg_replace('/\D/', '', $fone);   // tira ( ) - e espaços
//                             if (!in_array(strlen($fone), [10, 11]))               → 'Telefone inválido.'
//     Campo opcional (ex.: descrição)? Não precisa de validação.
//     Campo REMOVIDO? Apague o if dele.
if ($nome === '') {
    header('Location: ' . $voltar . 'erro=' . urlencode('O nome é obrigatório.'));
    exit;
}
if (!is_numeric($preco) || $preco < 0) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Preço inválido.'));
    exit;
}
if (!ctype_digit((string)$quantidade)) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Quantidade inválida.'));
    exit;
}

try {
    if ($id > 0) {
        // UPDATE
        // >>> ADAPTAR (3): troque a tabela, as colunas do SET e a chave do WHERE (se não for "id").
        //     Ex. livros:  UPDATE livros SET titulo = :titulo, autor = :autor,
        //                                    preco = :preco, estoque = :estoque WHERE id = :id
        //     Regra: cada "coluna = :nome" do SET precisa ter um ':nome' => valor no array abaixo.
        //     (O nome depois dos dois pontos é livre, mas tem que ser igual no SQL e no array.)
        $sql = "UPDATE produtos
                   SET nome = :nome, descricao = :descricao, preco = :preco, quantidade = :quantidade
                 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome'       => $nome,
            ':descricao'  => $descricao,
            ':preco'      => $preco,
            ':quantidade' => $quantidade,
            ':id'         => $id,
        ]);
        // >>> ADAPTAR (5): texto de sucesso. Ex.: 'Livro atualizado com sucesso!'
        $msg = 'Registro atualizado com sucesso!';
    } else {
        // CREATE
        // >>> ADAPTAR (4): troque a tabela, a lista de colunas e a lista de VALUES.
        //     Ex. livros:  INSERT INTO livros (titulo, autor, preco, estoque)
        //                  VALUES (:titulo, :autor, :preco, :estoque)
        //     Regra: nº de colunas = nº de valores = nº de itens no array abaixo, na mesma ordem.
        //     (NÃO coloque o id: ele é AUTO_INCREMENT, o banco preenche sozinho.)
        $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade)
                VALUES (:nome, :descricao, :preco, :quantidade)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome'       => $nome,
            ':descricao'  => $descricao,
            ':preco'      => $preco,
            ':quantidade' => $quantidade,
        ]);
        // >>> ADAPTAR (5): texto de sucesso. Ex.: 'Livro cadastrado com sucesso!'
        $msg = 'Registro cadastrado com sucesso!';
    }

    header('Location: index.php?msg=' . urlencode($msg));
} catch (PDOException $e) {
    // >>> ADAPTAR (opcional): coluna UNIQUE repetida (ex.: e-mail já cadastrado)? O MySQL devolve o código 1062.
    //     Para mostrar uma mensagem amigável, antes da linha abaixo coloque:
    //         if ($e->errorInfo[1] == 1062) {
    //             header('Location: ' . $voltar . 'erro=' . urlencode('Já existe um registro com este e-mail.'));
    //             exit;
    //         }
    header('Location: index.php?erro=' . urlencode('Erro ao salvar: ' . $e->getMessage()));
}
exit;
