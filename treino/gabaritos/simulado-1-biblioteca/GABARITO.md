# ✅ Gabarito: Simulado 1 (Biblioteca)

> ⚠️ Só abra depois de **tentar sozinho(a)**.
> Nesta pasta estão os **arquivos completos** (`index.php`, `form.php`, `salvar.php`, `excluir.php`, `conexao.php`, `banco.sql`),
> já no estado **final (depois da Fase 2)**. O `style.css` não muda: use o do projeto.
> Todos foram **testados** com banco de dados de verdade (cadastrar, editar, excluir, buscar, validações e regras).
> Seu código pode ser diferente e estar certo: o que vale é **funcionar** e cumprir o enunciado.

---

# FASE 1

## 1. `banco.sql`
O enunciado já trouxe o `CREATE TABLE` e os dados. Execute no Workbench (é o bloco do próprio enunciado).

## 2. `conexao.php`
```php
$banco   = 'biblioteca_db';
```

## 3. Localizar e substituir (`Ctrl+H`, **palavra inteira** ligada) nos 4 arquivos `.php`
| Procurar | Substituir por | Observação |
|----------|----------------|------------|
| `produtos` | `livros` | a tabela |
| `nome` | `titulo` | |
| `descricao` | `autor` | reaproveitando a coluna parecida |
| `quantidade` | `exemplares` | vale também para o alerta |

O `preco` **não existe** neste sistema: vai ser **removido** à mão nos próximos passos.
Os rótulos escritos com letra maiúscula (`Nome`, `Descrição`, `Qtd.`) o `Ctrl+H` não pega: troque você.

## 4. `index.php`
- **Busca e ordem** (2 `SELECT`):
  `SELECT * FROM livros WHERE titulo LIKE :b OR autor LIKE :b ORDER BY titulo ASC` e `SELECT * FROM livros ORDER BY titulo ASC`.
- **Colunas**: apague o `<th>` e o `<td>` do **Preço**. Acrescente Gênero e Ano. Fica: ID, Título, Autor, Gênero, Ano, Exemplares, Ações.
- **`colspan`** passa a ser **8** (7 colunas + Ações).
- Título da página e placeholder da busca.
- **Alerta** (`ALERTA-ESTOQUE`): no BLOCO 1 troque o SQL e os nomes de coluna; nos blocos 2 e 3 os textos.
  Na **Fase 1** o limite é **1** e o rótulo é **"Último exemplar"**:
```php
$LIMITE_ESTOQUE_BAIXO = 1;
$stmtAlerta = $pdo->prepare(
  "SELECT titulo, exemplares FROM livros WHERE exemplares <= :limite ORDER BY exemplares, titulo"
);
```

## 5. `form.php`
Array de valores padrão (no começo do arquivo; no gabarito já com os campos da Fase 2):
```php
$registro = ['titulo' => '', 'autor' => '', 'editora' => '', 'genero' => '', 'ano_publicacao' => '', 'exemplares' => ''];
```
- Remova o campo **Preço** e a chave `'preco'` do array.
- Acrescente os campos **Gênero** (texto) e **Ano de publicação** (`type="number" min="1500"`).
- Título da página: `'Editar Livro' : 'Novo Livro'`.

## 6. `salvar.php`
Variáveis e validações (a parte que mais muda), como estão no **arquivo final**. Já inclui o que a **Fase 2** pede: na Fase 1, ignore o que for dela:
```php
$id = (int)($_POST['id'] ?? 0);
$titulo = trim($_POST['titulo'] ?? '');
$autor = trim($_POST['autor'] ?? '');
$editora = trim($_POST['editora'] ?? '');
$genero = trim($_POST['genero'] ?? '');
$ano_publicacao = $_POST['ano_publicacao'] ?? '';
$exemplares = $_POST['exemplares'] ?? '';

if ($titulo === '') {
    header('Location: ' . $voltar . 'erro=' . urlencode('O título é obrigatório.'));
    exit;
}
if ($autor === '') {
    header('Location: ' . $voltar . 'erro=' . urlencode('O autor é obrigatório.'));
    exit;
}
if (!ctype_digit((string)$ano_publicacao) || $ano_publicacao < 1500 || $ano_publicacao > (int)date('Y')) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Ano de publicação inválido.'));
    exit;
}
if (!ctype_digit((string)$exemplares)) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Quantidade de exemplares inválida.'));
    exit;
}
```
- Remova tudo do `preco`: variável, `if` de validação, **`UPDATE`** e **`INSERT`** (colunas, `VALUES` e os **dois** arrays).
- O ano precisa de validação própria (1500 até o ano atual): está no bloco acima (`date('Y')` devolve o ano atual).
- Mensagens: `'Livro cadastrado com sucesso!'` e `'Livro atualizado com sucesso!'`.

## 7. `excluir.php`
Só a tabela, a chave e a mensagem de sucesso.

Troque `produtos` → `livros` e a mensagem para `'Livro excluído com sucesso!'`.

---

# FASE 2

## 2.1 Novo campo `editora`

**Banco** (mantém os livros já cadastrados):
```sql
ALTER TABLE livros ADD editora VARCHAR(80) NULL AFTER autor;
```
**Onde mexer** (a receita de "campo novo", 4 arquivos):

| Arquivo | O que fazer |
|---------|-------------|
| `index.php` | um `<th>Editora</th>` e um `<td><?= e($r['editora']) ?></td>` **depois de Autor**; `colspan` de 7 para **8** |
| `form.php` | `'editora' => ''` no `$registro` e um bloco `<div class="campo">` (opcional, `maxlength="80"`) |
| `salvar.php` | `$editora = trim($_POST['editora'] ?? '');` + coluna no **`UPDATE`** e no **`INSERT`** + `':editora' => $editora` nos **dois** arrays |

## 2.2 "Poucos exemplares" (até 2)
No `index.php` (blocos `ALERTA-ESTOQUE`):
```php
$LIMITE_ESTOQUE_BAIXO = 2;
```
e troque os rótulos **"Último exemplar"** por **"Poucos exemplares"** na faixa (bloco 2) e no selo (bloco 3).

---

## ⚠️ Erros comuns neste simulado
- Esquecer de remover o `preco` **em algum lugar**: dá `Undefined array key "preco"` ou erro de SQL.
- Comparar o ano com `date('Y')` **como texto**: use `(int)date('Y')` (já está no gabarito).
- Esquecer de trocar `quantidade` nos **dois** pontos do bloco 3 do alerta (`(int) $r['exemplares']`).
