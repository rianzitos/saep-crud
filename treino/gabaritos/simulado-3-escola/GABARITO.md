# ✅ Gabarito: Simulado 3 (Escola)

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
$banco   = 'escola_db';
```

## 3. Localizar e substituir (`Ctrl+H`, **palavra inteira** ligada) nos 4 arquivos `.php`
| Procurar | Substituir por | Observação |
|----------|----------------|------------|
| `produtos` | `alunos` | a tabela |
| `descricao` | `curso` | `nome` já existe e fica como está |
| `preco` | `nota1` | |
| `quantidade` | `nota2` | |

Os campos `email` e `data_nascimento` são **novos**. Os rótulos (`Preço`, `Qtd.`...) você troca à mão.

## 4. `index.php`
- **Busca e ordem:** `... WHERE nome LIKE :b OR email LIKE :b OR curso LIKE :b ORDER BY nome ASC`.
- **Média** é **coluna calculada** (não existe no banco). Calcule uma vez no começo da linha e use nas células:
```php
<?php $media = ($r['nota1'] + $r['nota2']) / 2; ?>
<td><strong><?= number_format($media, 1, ',', '.') ?></strong></td>
```
- **Situação** (reaproveita as classes do alerta, `selo-baixo` amarelo e `selo-falta` vermelho):
```php
<?php if ($media >= 6): ?>
  Aprovado
<?php elseif ($media >= 4): ?>
  <span class="selo selo-baixo">Recuperação</span>
<?php else: ?>
  <span class="selo selo-falta">Reprovado</span>
<?php endif; ?>
```
- **Faixas do topo** (o alerta de estoque adaptado). O SQL **calcula a média**; no gabarito já está com a fórmula da Fase 2 (÷ 3), na Fase 1 use `(nota1 + nota2) / 2`:
```php
$stmtAlerta = $pdo->query(
  "SELECT nome, (nota1 + nota2) / 2 AS media FROM alunos WHERE (nota1 + nota2) / 2 < 6 ORDER BY media, nome"
);
foreach ($stmtAlerta->fetchAll() as $item) {
  $txt = $item['nome'] . ' (' . number_format($item['media'], 1, ',', '.') . ')';
  if ($item['media'] < 4) {
    $emFalta[] = $txt;          // faixa vermelha: reprovados
  } else {
    $estoqueBaixo[] = $txt;     // faixa amarela: recuperação
  }
}
```
  (Os nomes `$emFalta` e `$estoqueBaixo` não importam: pode renomear.) Troque os textos das faixas para "Reprovados" e "Em recuperação".
- `colspan` = 9 na Fase 1 (8 colunas + Ações).

## 5. `form.php`
Array de valores padrão (no começo do arquivo; no gabarito já com os campos da Fase 2):
```php
$registro = ['nome' => '', 'email' => '', 'telefone' => '', 'curso' => '', 'data_nascimento' => '', 'nota1' => '', 'nota2' => '', 'nota3' => '0'];
```
- Select de curso, `<input type="date">` (opcional) e notas com `step="0.1" min="0" max="10"`.
- E-mail: `<input type="email">`.

## 6. `salvar.php`
Variáveis e validações (a parte que mais muda), como estão no **arquivo final**. Já inclui o que a **Fase 2** pede: na Fase 1, ignore o que for dela:
```php
$id = (int)($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');   // guarda só os dígitos
$curso = trim($_POST['curso'] ?? '');
$data_nascimento = ($_POST['data_nascimento'] ?? '') !== '' ? $_POST['data_nascimento'] : null;
$nota1 = $_POST['nota1'] ?? '';
$nota2 = $_POST['nota2'] ?? '';
$nota3 = $_POST['nota3'] ?? '';

if ($nome === '') {
    header('Location: ' . $voltar . 'erro=' . urlencode('O nome é obrigatório.'));
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . $voltar . 'erro=' . urlencode('E-mail inválido.'));
    exit;
}
if ($telefone !== '' && !in_array(strlen($telefone), [10, 11])) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Telefone inválido: informe DDD + número.'));
    exit;
}
if (!in_array($curso, ['Informática', 'Eletrônica', 'Mecânica', 'Administração'], true)) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Curso inválido.'));
    exit;
}
if ($data_nascimento !== null) {
    $d = DateTime::createFromFormat('Y-m-d', $data_nascimento);
    if (!$d || $d->format('Y-m-d') !== $data_nascimento || $data_nascimento > date('Y-m-d')) {
        header('Location: ' . $voltar . 'erro=' . urlencode('Data de nascimento inválida.'));
        exit;
    }
}
if (!is_numeric($nota1) || $nota1 < 0 || $nota1 > 10) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Nota 1 inválida (use de 0 a 10).'));
    exit;
}
if (!is_numeric($nota2) || $nota2 < 0 || $nota2 > 10) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Nota 2 inválida (use de 0 a 10).'));
    exit;
}
if (!is_numeric($nota3) || $nota3 < 0 || $nota3 > 10) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Nota 3 inválida (use de 0 a 10).'));
    exit;
}
```
- **E-mail válido:** `filter_var($email, FILTER_VALIDATE_EMAIL)`.
- **Data opcional:** se vier vazia, grave `NULL` (senão o MySQL recusa). Está na linha da variável.
- **E-mail repetido:** o MySQL devolve o código **1062**. Trate no `catch`, antes do redirecionamento padrão:
```php
if ($e->errorInfo[1] == 1062) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Já existe um aluno com este e-mail.'));
    exit;
}
```

## 7. `excluir.php`
Só a tabela, a chave e a mensagem de sucesso.

Troque `produtos` → `alunos` e a mensagem (`'Aluno excluído com sucesso!'`).

---

# FASE 2

## 2.1 Terceira nota (`nota3`)

**Banco:**
```sql
ALTER TABLE alunos ADD nota3 DECIMAL(4,2) NOT NULL DEFAULT 0 AFTER nota2;
```
**Onde mexer:**

| Arquivo | O que fazer |
|---------|-------------|
| `index.php` | `<th>N3</th>` e `<td>` da nota 3; `colspan` +1; **a média nos 3 lugares**: `$media = (n1 + n2 + n3) / 3`, **SQL das faixas** e o `WHERE` do SQL |
| `form.php` | `'nota3' => '0'` no `$registro` e um campo igual às outras notas |
| `salvar.php` | variável, validação 0 a 10, `UPDATE`/`INSERT` e os dois arrays |

## 2.2 Telefone (só dígitos)

**Banco:**
```sql
ALTER TABLE alunos ADD telefone VARCHAR(11) NULL AFTER email;
```
No `salvar.php`:
```php
$telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');   // tira ( ) - e espaços, fica só dígitos
if ($telefone !== '' && !in_array(strlen($telefone), [10, 11])) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Telefone inválido: informe DDD + número.'));
    exit;
}
```
No `form.php`: `<input type="tel">` com `placeholder="(11) 98888-7777"`. Nos outros pontos, é a receita de campo novo (`index.php` é opcional, `form.php`, `salvar.php`).

---

## ⚠️ Erros comuns neste simulado
- Esquecer que a média também está **dentro do SQL** das faixas (não só na tabela).
- Dividir por 2 em um lugar e por 3 em outro (Fase 2): troque **os três** (lista, SQL das faixas e `$media`).
- Gravar data vazia como `''`: o MySQL recusa. Use `NULL`.
- Mostrar o erro de SQL cru no e-mail repetido: o enunciado pede a mensagem amigável.
