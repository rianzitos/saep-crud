# CRUD modelo para o SAEP (PHP + MySQL + PDO)

## Como rodar
1. Coloque a pasta em `htdocs` (XAMPP) ou `www` (WAMP).
2. Inicie Apache e MySQL.
3. Importe `banco.sql` no phpMyAdmin.
   (Atenção: ele recria a tabela `produtos` e **apaga os dados atuais** dela.)
4. Acesse `http://localhost/saep-crud/`.

## Arquivos
| Arquivo       | Função                                   |
|---------------|------------------------------------------|
| banco.sql     | Cria o banco, a tabela e dados de exemplo|
| conexao.php   | Conexão PDO (host, banco, usuário, senha)|
| index.php     | READ  - lista + busca + alerta de estoque|
| form.php      | Formulário de CREATE e UPDATE            |
| salvar.php    | Executa INSERT / UPDATE (com validação)  |
| excluir.php   | Executa DELETE                           |
| style.css     | Estilo base (estrutura; cores nas variáveis do :root) |
| temas/*.css   | 5 visuais prontos (escolhidos no conexao.php) |

## Como achar o que mudar (Ctrl+F no editor)
| Procure por       | O que você encontra                                              |
|-------------------|------------------------------------------------------------------|
| `ADAPTAR`         | Todo ponto do código que muda conforme a prova (numerados, com exemplos) |
| `ALERTA-ESTOQUE`  | Os blocos do alerta de estoque (INÍCIO e FIM de cada um)         |

Cada arquivo começa com um resumo "O QUE MUDAR NESTE ARQUIVO". Os exemplos dos comentários usam
**LIVROS** no lugar de produtos (`nome→titulo`, `descricao→autor`, `quantidade→estoque`).

## Alerta de estoque (extra — só em `index.php` e `style.css`)
- **Em falta** (quantidade 0): faixa vermelha no topo + selo vermelho na linha.
- **Estoque baixo** (de 1 até o limite, padrão **5**): faixa amarela no topo + selo amarelo na linha.
- O limite fica em `$LIMITE_ESTOQUE_BAIXO` (regra "menor **ou igual**": "menos de 10" → use 9).
- Coluna com outro nome (ex.: `estoque`)? Se usar localizar-e-substituir nos arquivos, já troca tudo.

**Se a prova NÃO pedir o alerta — dois jeitos de tirar:**
1. **Rápido:** em `index.php`, troque `$ALERTA_ESTOQUE_ATIVO = true;` por `false;`. Pronto.
2. **Limpo:** Ctrl+F `ALERTA-ESTOQUE` e apague os 3 blocos do `index.php`, cada um da linha
   "INÍCIO" até a linha "FIM" (inclusive). No `style.css` o bloco é opcional.

## Temas visuais (CSS) — 5 prontos
**Para trocar o visual, mude UMA linha no `conexao.php`** (procure por `TROQUE AQUI`):

```php
$TEMA = 'oceano';   // <<< TROQUE AQUI
```

| `$TEMA`        | Identidade                                                        |
|----------------|-------------------------------------------------------------------|
| `'oceano'`     | Glassmorphism: vidro fosco azul/ciano sobre gradiente, tema escuro |
| `'neon'`       | Dark estilo cyberpunk, com brilho ciano / rosa / roxo              |
| `'por-do-sol'` | Gradiente laranja → rosa → roxo, vidro claro, título com gradiente |
| `'floresta'`   | Verde limpo e suave, claro, tabela zebrada                         |
| `'brutalista'` | Neo-brutalismo: amarelo + rosa, bordas pretas, sombras duras       |
| `''` (vazio)   | Visual padrão simples (só o `style.css`, azul e cinza)             |

- O nome é o do arquivo em `temas/` sem o `.css` (ex.: `'neon'` → `temas/neon.css`).
- **Só mudar as cores?** Abra `temas/nome-do-tema.css` e edite as variáveis do `:root` (cada uma tem comentário).
- **Visual próprio?** Copie um tema (`temas/oceano.css` → `temas/meu.css`), troque as cores e use `$TEMA = 'meu';`.
- Os temas já estilizam o alerta de estoque e os campos de data, e-mail, senha, select e textarea.
- Funciona offline: não usa fontes nem bibliotecas da internet.
- **Prova não quer tema?** Deixe as aspas vazias (`''`) e ajuste as cores no `:root` do `style.css`.

## Checklist para adaptar na prova
1. **banco.sql**: troque o nome da tabela e as colunas. (Se a prova já entregar o banco, use o dela.)
2. **conexao.php**: confira host, usuário, senha e **nome do banco**.
3. **Localizar e substituir** `produtos` pelo nome da nova tabela (index, form, salvar, excluir).
4. **index.php**: ajuste os `<th>` e os `<td>` das colunas (mesma ordem) e o `colspan`.
5. **form.php**: ajuste os campos e o array `$registro` do começo.
6. **salvar.php**: ajuste variáveis, validações, e os `INSERT`/`UPDATE`.
7. **Visual**: escolha o tema em `$TEMA` (`conexao.php`) ou ajuste as cores no `:root` do `style.css`.
8. **Alerta de estoque**: manter, ajustar o limite/coluna, ou remover (ver acima).
9. **Testar**: cadastrar, editar, excluir, buscar — e conferir se o alerta aparece com estoque 0 e baixo.

## Receita: adicionar um campo novo (ex.: `categoria`, texto)
O campo novo aparece em **4 arquivos**:
1. `banco.sql` → uma linha no `CREATE TABLE` (`categoria VARCHAR(50) NULL,`) e no `INSERT`.
   (Se a tabela já existe: `ALTER TABLE produtos ADD categoria VARCHAR(50) NULL;`)
2. `index.php` → um `<th>` e um `<td>` (na mesma posição), e aumente 1 no `colspan`.
3. `form.php` → chave no array `$registro` + um bloco `<div class="campo">` com o `<input>`.
4. `salvar.php` → variável `$_POST`, validação (se precisar), e o campo no `UPDATE` (SET + array)
   e no `INSERT` (colunas + VALUES + array).

## Se a chave primária não se chamar `id` (ex.: `codigo`, `id_livro`)
Troque `id` pela coluna real **só no SQL e no `$r['id']`**: `ORDER BY id` e `$r['id']` (3x) no
`index.php`, `WHERE id = :id` no `form.php`, `salvar.php` e `excluir.php`.
O `name="id"` do formulário e o `?id=` da URL podem continuar iguais.
