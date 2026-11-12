# CRUD modelo para o SAEP (PHP + MySQL + PDO)

## Como rodar
1. Coloque a pasta em `htdocs` (XAMPP) ou `www` (WAMP).
2. Inicie Apache e MySQL.
3. Importe `banco.sql` no phpMyAdmin.
4. Acesse `http://localhost/saep-crud/`.

## Arquivos
| Arquivo       | Função                                   |
|---------------|------------------------------------------|
| banco.sql     | Cria o banco, a tabela e dados de exemplo|
| conexao.php   | Conexão PDO (host, banco, usuário, senha)|
| index.php     | READ  - lista + busca                    |
| form.php      | Formulário de CREATE e UPDATE            |
| salvar.php    | Executa INSERT / UPDATE (com validação)  |
| excluir.php   | Executa DELETE                           |
| style.css     | Estilo (cores nas variáveis do :root)    |

## Checklist para adaptar na prova
1. **banco.sql**: troque o nome da tabela e as colunas.
2. **conexao.php**: confira usuário, senha e nome do banco.
3. **Localizar e substituir** `produtos` pelo nome da nova tabela (index, form, salvar, excluir).
4. **index.php**: ajuste os `<th>` e os `<td>` das colunas.
5. **form.php**: ajuste os campos e o array `$registro` do começo.
6. **salvar.php**: ajuste variáveis, validações, e os `INSERT`/`UPDATE`.
7. **style.css**: troque as cores no `:root` se pedirem outro visual.
