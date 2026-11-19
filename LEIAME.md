# CRUD modelo para o SAEP (PHP + MySQL + PDO)

Sistema simples de **cadastro de produtos**: listar, buscar, cadastrar, editar e excluir,
com **alerta de estoque** (em falta / estoque baixo). O código é todo comentado para você
adaptar ao que a prova pedir.

> 🎬 **Prefere ver em vídeo?** Assista ao `tutorial/tutorial-saep-crud.mp4`: mostra tudo
> deste guia, do XAMPP até adaptar o sistema para a prova.

---

## 1. Passo a passo do zero (faça na ordem)

### Passo 1: Ligar o XAMPP
1. Abra o **XAMPP Control Panel** (no Windows: menu Iniciar → "XAMPP Control Panel").
2. Clique em **Start** na linha do **Apache**. Ele fica verde.
3. O **MySQL**: depende do banco que o computador usa.
   - Se você vai usar o **MySQL do XAMPP**: clique em **Start** no **MySQL** também.
   - Se o computador tem o **MySQL Server** instalado à parte (o que vem com o Workbench),
     **NÃO** ligue o MySQL do XAMPP: os dois brigam pela mesma porta (3306).
     Ligue só o Apache.

### Passo 2: Colocar a pasta do projeto no `htdocs`
O Apache só "enxerga" o que está dentro de **`C:\xampp\htdocs`**.

- **Baixando o ZIP do GitHub:** botão verde **Code → Download ZIP**, extraia e
  coloque a pasta em `C:\xampp\htdocs`. Renomeie para **`saep-crud`**.
  ⚠️ Cuidado com **pasta dentro de pasta** (`htdocs\saep-crud-main\saep-crud-main\...`).
  O certo é o `index.php` ficar em **`C:\xampp\htdocs\saep-crud\index.php`**.
- **Com Git:** abra o terminal em `C:\xampp\htdocs` e rode
  `git clone https://github.com/rianzitos/saep-crud.git`

### Passo 3: Criar o banco de dados (escolha UM jeito)

**Jeito A: MySQL Workbench**
1. Na tela inicial do Workbench, clique no **+** ao lado de **MySQL Connections**.
2. Preencha:
   - **Connection Name:** `saep` (qualquer nome)
   - **Hostname:** `127.0.0.1`   **Port:** `3306`   **Username:** `root`
   - **Password:** clique em **Store in Vault...** e digite a senha
     (MySQL do XAMPP: deixe **vazio**; MySQL Server: a senha criada na instalação).
3. Clique em **Test Connection**. Deu "Successfully made the MySQL connection"? Clique **OK**.
   (Se aparecer um aviso de versão incompatível, é normal com o XAMPP: clique em **Continue Anyway**.)
4. Dê **dois cliques** na conexão para abrir.
5. Menu **File → Open SQL Script...** e escolha o **`banco.sql`** do projeto.
6. Clique no **primeiro raio ⚡** da barra do editor (ou `Ctrl+Shift+Enter`) para executar o arquivo inteiro.
7. No painel **SCHEMAS** (à esquerda), clique em **atualizar 🔄**. O banco **`saep_db`** aparece.

**Jeito B: phpMyAdmin** (só com o MySQL do XAMPP ligado)
1. Abra `http://localhost/phpmyadmin`.
2. Aba **Importar** → **Escolher arquivo** → `banco.sql` → **Executar** (lá embaixo).

> ⚠️ Executar o `banco.sql` de novo **apaga e recria** a tabela (os dados cadastrados somem).

### Passo 4: Conferir a senha no `conexao.php`
Abra `conexao.php` e confira a linha `$senha`:
- MySQL do XAMPP → `$senha = '';` (vazio)
- MySQL Server / Workbench → a **mesma senha** que você usa no Workbench (ex.: `'123456'`)

### Passo 5: Abrir o sistema
No navegador: **`http://localhost/saep-crud/`**

Apareceu a lista de produtos com as faixas de alerta? Está tudo funcionando. 🎉

---

## 2. Deu erro? (problemas mais comuns)

| O que aparece | O que fazer |
|---|---|
| `Access denied for user 'root'` | Senha errada no `conexao.php`. Teste `''` (vazio) ou a senha do Workbench. |
| `Unknown database 'saep_db'` | O `banco.sql` não foi executado, ou o nome em `$banco` está diferente. |
| `Connection refused` / `No connection could be made` | O MySQL está desligado. Ligue no XAMPP (ou o serviço do MySQL Server). |
| `Object not found!` / erro 404 | Pasta no lugar errado ou com outro nome. Confira o Passo 2 e o endereço. |
| XAMPP: MySQL não liga ("Port 3306 in use") | Já tem um MySQL Server ligado. Use ele (Jeito A) e ligue só o Apache. |
| Página sem cores, "tudo branco e feio" | O `style.css` sumiu ou foi renomeado. Ele tem que estar ao lado do `index.php`. |
| Mudei o CSS e nada mudou | Atualize com `Ctrl+F5` (o navegador guarda o CSS antigo). |

---

## 3. O que tem em cada arquivo
| Arquivo       | Para que serve |
|---------------|----------------|
| `banco.sql`   | Cria o banco, a tabela e os dados de exemplo |
| `conexao.php` | Conexão com o banco (host, banco, usuário, **senha**) |
| `index.php`   | **Lista** os produtos + busca + alerta de estoque |
| `form.php`    | **Formulário** de cadastrar e editar |
| `salvar.php`  | Grava no banco (INSERT / UPDATE), com validação |
| `excluir.php` | Apaga do banco (DELETE) |
| `style.css`   | **O único CSS que o sistema usa** (visual) |
| `temas/`      | Catálogo com 6 visuais prontos. **Nenhuma página usa esta pasta** |
| `tutorial/`   | Vídeo explicando tudo (pode apagar, não interfere no sistema) |

---

## 4. Visual (CSS): como funciona

- As páginas carregam **só um arquivo: `style.css`**.
- A pasta **`temas/`** é só um **catálogo**. Cada arquivo lá dentro é um `style.css` completo, pronto para usar.
- **Pode apagar a pasta `temas/`** depois de escolher: nada quebra.
- **Não pode apagar o `style.css`**: sem ele a página fica sem estilo.

**Para trocar o visual (3 passos):**
1. Abra o tema escolhido, ex.: `temas/neon.css`, e copie **tudo** (`Ctrl+A`, `Ctrl+C`).
2. Abra o `style.css`, selecione **tudo** (`Ctrl+A`) e **cole por cima** (`Ctrl+V`). Salve (`Ctrl+S`).
3. No navegador, `Ctrl+F5`.

| Arquivo em `temas/` | Visual |
|---|---|
| `padrao.css`     | Simples e limpo, azul e cinza (é o que vem no `style.css`) |
| `oceano.css`     | Glassmorphism: vidro fosco azul/ciano sobre gradiente, escuro |
| `neon.css`       | Dark estilo cyberpunk, brilho ciano / rosa / roxo |
| `por-do-sol.css` | Gradiente laranja → rosa → roxo, vidro claro |
| `floresta.css`   | Verde limpo e suave, claro |
| `brutalista.css` | Amarelo + rosa, bordas pretas e sombras duras |

**Só trocar as cores?** No `style.css`, mude os valores das variáveis do `:root` (no começo do arquivo).
Cada variável tem um comentário dizendo onde ela aparece na tela.

---

## 5. Adaptar para a prova

### Como achar o que mudar (`Ctrl+F` no editor)
| Procure por       | O que você encontra |
|-------------------|---------------------|
| `ADAPTAR`         | Todo ponto do código que muda conforme a prova (numerados, com exemplos) |
| `ALERTA-ESTOQUE`  | Os blocos do alerta de estoque (INÍCIO e FIM de cada um) |

Cada arquivo começa com um resumo **"O QUE MUDAR NESTE ARQUIVO"**. Os exemplos usam
**LIVROS** no lugar de produtos (`nome→titulo`, `descricao→autor`, `quantidade→estoque`).

### Checklist (na ordem)
1. **banco.sql**: nome do banco, nome da tabela e colunas. (Se a prova já entregar o banco, use o dela.)
2. **conexao.php**: confira **senha** e **nome do banco**.
3. **Localizar e substituir** (`Ctrl+H`) `produtos` pelo nome da nova tabela em `index`, `form`, `salvar`, `excluir`.
   Faça o mesmo para cada coluna renomeada (ex.: `nome` → `titulo`).
4. **index.php**: `<th>` e `<td>` das colunas (mesma ordem) e o `colspan`.
5. **form.php**: os campos e o array `$registro` do começo.
6. **salvar.php**: variáveis, validações, `INSERT` e `UPDATE`.
7. **Visual**: escolha um tema (seção 4) ou mude as cores no `:root` do `style.css`.
8. **Alerta de estoque**: manter, ajustar ou remover (abaixo).
9. **Testar tudo**: cadastrar, editar, excluir, buscar.

### Adicionar um campo novo (ex.: `categoria`, texto)
O campo novo aparece em **4 arquivos**:
1. `banco.sql` → uma linha no `CREATE TABLE` (`categoria VARCHAR(50) NULL,`) e no `INSERT`.
   (Se a tabela já existe: `ALTER TABLE produtos ADD categoria VARCHAR(50) NULL;`)
2. `index.php` → um `<th>` e um `<td>` (na mesma posição), e aumente 1 no `colspan`.
3. `form.php` → chave no array `$registro` + um bloco `<div class="campo">` com o `<input>`.
4. `salvar.php` → variável `$_POST`, validação (se precisar), e o campo no `UPDATE` (SET + array)
   e no `INSERT` (colunas + VALUES + array).

### Chave primária com outro nome (ex.: `codigo`, `id_livro`)
Troque `id` pela coluna real **só no SQL e no `$r['id']`**: `ORDER BY id` e `$r['id']` (3x) no
`index.php`, `WHERE id = :id` no `form.php`, `salvar.php` e `excluir.php`.
O `name="id"` do formulário e o `?id=` da URL podem continuar iguais.

### Alerta de estoque
- **Em falta** (quantidade 0): faixa vermelha no topo + selo vermelho na linha.
- **Estoque baixo** (de 1 até o limite, padrão **5**): faixa amarela + selo amarelo.
- O limite fica em `$LIMITE_ESTOQUE_BAIXO` no `index.php` (regra "menor **ou igual**": "menos de 10" → use 9).

**A prova NÃO pediu o alerta? Dois jeitos de tirar:**
1. **Rápido:** no `index.php`, troque `$ALERTA_ESTOQUE_ATIVO = true;` por `false;`.
2. **Limpo:** `Ctrl+F` `ALERTA-ESTOQUE` e apague os 3 blocos do `index.php`, cada um da linha
   "INÍCIO" até a linha "FIM" (inclusive). No `style.css` o bloco é opcional.
