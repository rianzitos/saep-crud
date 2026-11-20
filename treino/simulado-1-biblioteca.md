# Simulado 1: Biblioteca escolar

⏱ **Tempo sugerido:** 60 minutos · **Nível:** ⭐ fácil

> **Antes de começar:** trabalhe numa **cópia** da pasta do projeto, ligue o cronômetro e **não abra** a pasta `gabaritos/`.
> Guia completo de como treinar: [`LEIAME.md`](LEIAME.md).

## O que este simulado treina
- Trocar o assunto do sistema (de produtos para **livros**).
- **Remover** uma coluna que não existe mais (`preco`).
- Adicionar colunas novas (gênero, ano).
- Adaptar o **alerta de estoque** para outro assunto ("acervo").
- Na Fase 2: adicionar um campo com `ALTER TABLE` e mudar uma regra.

---

# 📄 PROPOSTA (como seria entregue na prova)

## Contexto
A biblioteca da escola controla o acervo em uma planilha. A bibliotecária quer um sistema web simples para **cadastrar os livros** e **saber rapidamente quais estão acabando**, para pedir reposição.

## Banco de dados
Banco: **`biblioteca_db`** · Tabela: **`livros`**

| Coluna | Tipo | Regras |
|--------|------|--------|
| `id` | INT, chave primária, auto incremento | |
| `titulo` | VARCHAR(150) | obrigatório |
| `autor` | VARCHAR(100) | obrigatório |
| `genero` | VARCHAR(50) | opcional |
| `ano_publicacao` | INT | obrigatório, entre **1500** e o **ano atual** |
| `exemplares` | INT | obrigatório, 0 ou mais (padrão 0) |

**Estrutura (execute no Workbench):**

```sql
CREATE DATABASE IF NOT EXISTS biblioteca_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca_db;

CREATE TABLE livros (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  titulo          VARCHAR(150) NOT NULL,
  autor           VARCHAR(100) NOT NULL,
  genero          VARCHAR(50)  NULL,
  ano_publicacao  INT          NOT NULL,
  exemplares      INT          NOT NULL DEFAULT 0
);

INSERT INTO livros (titulo, autor, genero, ano_publicacao, exemplares) VALUES
('Dom Casmurro', 'Machado de Assis', 'Romance', 1899, 4),
('O Cortiço', 'Aluísio Azevedo', 'Romance', 1890, 1),
('Vidas Secas', 'Graciliano Ramos', 'Romance', 1938, 0),
('Memórias Póstumas de Brás Cubas', 'Machado de Assis', 'Romance', 1881, 2),
('O Pequeno Príncipe', 'Antoine de Saint-Exupéry', 'Infantil', 1943, 6);
```

## Requisitos: FASE 1

Marque conforme for concluindo:

- [ ] **R1.** Criar o banco `biblioteca_db` e a tabela `livros` com os dados de exemplo.
- [ ] **R2.** Ajustar a conexão (`conexao.php`) para o novo banco.
- [ ] **R3.** A **listagem** mostra: ID, Título, Autor, Gênero, Ano, Exemplares e Ações. (Não existe mais preço!)
- [ ] **R4.** **Cadastrar e editar** com todos os campos. Título e autor são obrigatórios.
- [ ] **R5.** **Validar o ano** no servidor: entre 1500 e o ano atual, com mensagem de erro clara.
- [ ] **R6.** A **busca** procura por título **ou** autor.
- [ ] **R7.** A lista aparece em **ordem alfabética** pelo título.
- [ ] **R8.** **Alerta de acervo** (adapte o alerta de estoque que já existe):
  - faixa **vermelha** "Indisponíveis" com os livros de **0 exemplares**;
  - faixa **amarela** "Último exemplar" com os livros de **exatamente 1 exemplar**;
  - um **selo** ao lado do número de exemplares na tabela ("Indisponível" / "Último exemplar").
- [ ] **R9.** Textos: título da página **"Livros"**; mensagens **"Livro cadastrado com sucesso!"**, **"Livro atualizado com sucesso!"** e **"Livro excluído com sucesso!"**; botão do formulário com título "Novo Livro" / "Editar Livro".
- [ ] **R10.** **Excluir** continua funcionando.
- [ ] **R11.** Tudo funciona **sem erros na tela** (nenhum `Warning`, `Notice` ou erro de SQL).

<details>
<summary><b>🔒 FASE 2: mudança de requisito (só abra depois de terminar e testar a Fase 1!)</b></summary>

A bibliotecária pediu duas mudanças depois de ver o sistema funcionando:

- [ ] **F2.1 (15 pts).** Novo campo **`editora`** (texto, até 80 caracteres, **opcional**). Deve aparecer na **lista** (logo depois de Autor) e no **formulário**.
  Importante: o banco **já tem livros cadastrados**, então use `ALTER TABLE` em vez de recriar a tabela.
- [ ] **F2.2 (15 pts).** O alerta "Último exemplar" passa a valer para **2 exemplares ou menos** (e não só 1). O rótulo muda para **"Poucos exemplares"** (faixa e selo).

</details>

## Critérios de avaliação (Fase 1: 100 pontos)

| Requisito | Pontos |
|-----------|--------|
| R1 Banco e tabela | 10 |
| R2 Conexão | 5 |
| R3 Listagem (colunas certas, sem preço) | 15 |
| R4 Cadastro e edição | 15 |
| R5 Validação do ano | 10 |
| R6 Busca por título ou autor | 10 |
| R7 Ordem alfabética | 5 |
| R8 Alerta de acervo (faixas e selos) | 15 |
| R9 Textos e mensagens | 5 |
| R10 Exclusão | 5 |
| R11 Funciona sem erros na tela | 5 |

**Fase 2:** bônus de **30 pontos** (cada item vale o que está indicado).

## Dicas
- O `preco` do modelo não existe aqui: lembre de tirá-lo em **todos** os lugares (lista, formulário e `salvar.php`).
- O alerta usa a coluna `quantidade`: aqui ela se chama `exemplares`.
- Procure por `ADAPTAR` e por `ALERTA-ESTOQUE` nos arquivos.
