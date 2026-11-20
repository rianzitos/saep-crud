# ✅ Gabarito: Simulado 2 (Clínica)

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
$banco   = 'clinica_db';
```

## 3. Localizar e substituir (`Ctrl+H`, **palavra inteira** ligada) nos 4 arquivos `.php`
| Procurar | Substituir por | Observação |
|----------|----------------|------------|
| `produtos` | `consultas` | a tabela |
| `nome` | `paciente` | |
| `descricao` | `especialidade` | |
| `preco` | `valor` | |

`quantidade` **não existe** aqui (sairá junto com o alerta). Os campos `data_consulta`, `horario`, `status` e `observacoes` são **novos**.

### ⚠️ A chave primária `codigo`
**Não** faça `Ctrl+H` de `id` para `codigo` (o `id` aparece em muitos lugares que **não** são a coluna, como o `name="id"` do formulário).
Troque só onde é a **coluna do banco**:

| Arquivo | Onde |
|---------|------|
| `index.php` | os **3** `$r['id']` da tabela (célula do código, link Editar e botão Excluir), e a ordem |
| `form.php` | `SELECT * FROM consultas WHERE codigo = :id` |
| `salvar.php` | `WHERE codigo = :id` no `UPDATE` |
| `excluir.php` | `DELETE FROM consultas WHERE codigo = :id` |

O `name="id"` do formulário, o `$_POST['id']` e o `?id=` da URL **continuam como estão**.

## 4. `index.php`
- **Busca e ordem:**
  `SELECT * FROM consultas WHERE paciente LIKE :b OR especialidade LIKE :b ORDER BY data_consulta ASC, horario ASC`
  (e o mesmo `ORDER BY` no `SELECT` sem busca).
- **Colunas** (Fase 1): Código, Paciente, Especialidade, Data, Horário, Valor, Status.
- **Data, hora e dinheiro formatados:**
```php
<td><?= date('d/m/Y', strtotime($r['data_consulta'])) ?></td>
<td><?= substr($r['horario'], 0, 5) ?></td>
<td>R$ <?= number_format($r['valor'], 2, ',', '.') ?></td>
```
- **Remover o alerta por completo:** `Ctrl+F` por `ALERTA-ESTOQUE` e apague **os 3 blocos**, cada um da linha `INÍCIO` até a linha `FIM` (inclusive).
  Não basta trocar `true` por `false`: o enunciado pede para **remover**.
- `colspan` = 8 na Fase 1 (7 colunas + Ações).

## 5. `form.php`
Array de valores padrão (no começo do arquivo; no gabarito já com os campos da Fase 2):
```php
$registro = ['paciente' => '', 'especialidade' => '', 'convenio' => 'Particular', 'data_consulta' => '', 'horario' => '', 'valor' => '', 'status' => 'Agendada', 'observacoes' => ''];
```
- `$registro` com **todas** as chaves; o status já começa com `'Agendada'` para o select vir marcado em consulta nova.
- **Lista suspensa** (a opção salva vem marcada na edição):
```html
<select id="especialidade" name="especialidade" required>
  <option value="">Selecione...</option>
  <option value="Cardiologia" <?= $registro['especialidade'] === 'Cardiologia' ? 'selected' : '' ?>>Cardiologia</option>
  ...
</select>
```
- **Data**: `<input type="date">` · **Hora**: `<input type="time" value="<?= e(substr($registro['horario'], 0, 5)) ?>">`
  (o banco devolve `10:30:00`, e o campo de hora quer `10:30`) · **Texto grande**: `<textarea>`.

## 6. `salvar.php`
Variáveis e validações (a parte que mais muda), como estão no **arquivo final**. Já inclui o que a **Fase 2** pede: na Fase 1, ignore o que for dela:
```php
$id = (int)($_POST['id'] ?? 0);
$paciente = trim($_POST['paciente'] ?? '');
$especialidade = trim($_POST['especialidade'] ?? '');
$convenio = trim($_POST['convenio'] ?? '');
$data_consulta = $_POST['data_consulta'] ?? '';
$horario = $_POST['horario'] ?? '';
$valor = $_POST['valor'] ?? '';
$status = trim($_POST['status'] ?? '');
$observacoes = trim($_POST['observacoes'] ?? '');

if ($paciente === '') {
    header('Location: ' . $voltar . 'erro=' . urlencode('O nome do paciente é obrigatório.'));
    exit;
}
if (!in_array($especialidade, ['Clínico Geral', 'Cardiologia', 'Dermatologia', 'Pediatria'], true)) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Especialidade inválida.'));
    exit;
}
if (!in_array($convenio, ['Particular', 'Unimed', 'Bradesco Saúde', 'SUS'], true)) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Convênio inválido.'));
    exit;
}
$d = DateTime::createFromFormat('Y-m-d', $data_consulta);
if (!$d || $d->format('Y-m-d') !== $data_consulta) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Data da consulta inválida.'));
    exit;
}
if (!preg_match('/^\d{2}:\d{2}$/', $horario)) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Horário inválido.'));
    exit;
}
if (!is_numeric($valor) || $valor < 0) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Valor inválido.'));
    exit;
}
if (!in_array($status, ['Agendada', 'Realizada', 'Cancelada'], true)) {
    header('Location: ' . $voltar . 'erro=' . urlencode('Status inválido.'));
    exit;
}
// Regra de negócio (FASE 2): consulta cancelada não é cobrada.
if ($status === 'Cancelada') {
    $valor = 0;
}
```
- Select (especialidade e status): `in_array($valor, [...], true)`: o usuário pode alterar o HTML pelo navegador, então confira **no servidor**.
- Data: `DateTime::createFromFormat('Y-m-d', ...)` e conferir que o resultado é igual ao texto (assim `31/02` é recusado).
- `observacoes`: não precisa de validação (opcional).

## 7. `excluir.php`
Só a tabela, a chave e a mensagem de sucesso.

Troque `produtos` → `consultas`, a **chave** (`WHERE codigo = :id`) e a mensagem (`'Consulta excluída com sucesso!'`).

---

# FASE 2

## 2.1 Novo campo `convenio`

**Banco:**
```sql
ALTER TABLE consultas ADD convenio VARCHAR(50) NOT NULL DEFAULT 'Particular' AFTER especialidade;
```
(o `DEFAULT` faz as consultas **já cadastradas** ficarem como "Particular".)

| Arquivo | O que fazer |
|---------|-------------|
| `index.php` | `<th>Convênio</th>` e `<td><?= e($r['convenio']) ?></td>` **depois de Especialidade**; `colspan` de 8 para **9** |
| `form.php` | `'convenio' => 'Particular'` no `$registro` + bloco com `<select>` (Particular, Unimed, Bradesco Saúde, SUS) |
| `salvar.php` | `$convenio = trim(...)`, validação com `in_array`, coluna no `UPDATE`/`INSERT` e `':convenio'` nos dois arrays |

## 2.2 Consulta cancelada não é cobrada
No `salvar.php`, **depois das validações** e **antes do `try`**:
```php
if ($status === 'Cancelada') {
    $valor = 0;
}
```
(O valor é **forçado no servidor**: não adianta confiar no formulário.)

---

## ⚠️ Erros comuns neste simulado
- Trocar **todos** os `id` por `codigo` com `Ctrl+H`: quebra o formulário. Troque só os pontos da tabela acima.
- Deixar sobrar um pedaço do alerta de estoque: dá `Undefined variable` ou `Undefined array key "quantidade"`.
- Campo de hora com `10:30:00` no `value`: o navegador mostra segundos. Use `substr(..., 0, 5)`.
