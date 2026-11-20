# Simulado 3: Escola: boletim de alunos

⏱ **Tempo sugerido:** 90 minutos · **Nível:** ⭐⭐⭐ desafio

> **Antes de começar:** trabalhe numa **cópia** da pasta do projeto, ligue o cronômetro e **não abra** a pasta `gabaritos/`.
> Guia completo de como treinar: [`LEIAME.md`](LEIAME.md).

## O que este simulado treina
- **Coluna calculada** (a média não existe no banco).
- Texto que depende de uma **regra** (Aprovado / Recuperação / Reprovado).
- **Reaproveitar o alerta** para outro propósito (alunos em risco) e fazer a conta **dentro do SQL**.
- E-mail **único** e tratamento do erro de repetição.
- Mais validações: e-mail, notas de 0 a 10, data opcional que não pode ser futura.
- Na Fase 2: **dois campos novos** e uma mudança que afeta **vários lugares** (a fórmula da média).

---

# 📄 PROPOSTA (como seria entregue na prova)

## Contexto
A secretaria de uma escola técnica quer um **boletim digital**: cadastrar os alunos com suas notas e ver, na hora, **quem está aprovado, em recuperação ou reprovado**, para chamar os responsáveis.

## Banco de dados
Banco: **`escola_db`** · Tabela: **`alunos`**

| Coluna | Tipo | Regras |
|--------|------|--------|
| `id` | INT, chave primária, auto incremento | |
| `nome` | VARCHAR(100) | obrigatório |
| `email` | VARCHAR(120) | obrigatório, **válido** e **único** (`UNIQUE`) |
| `curso` | VARCHAR(50) | **lista fixa**: Informática, Eletrônica, Mecânica, Administração |
| `data_nascimento` | DATE | **opcional**; se informada, tem que ser válida e **não pode ser futura** |
| `nota1`, `nota2` | DECIMAL(4,2) | obrigatórias, de **0 a 10** |

**Estrutura (execute no Workbench):**

```sql
CREATE DATABASE IF NOT EXISTS escola_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE escola_db;

CREATE TABLE alunos (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  nome             VARCHAR(100)  NOT NULL,
  email            VARCHAR(120)  NOT NULL UNIQUE,
  curso            VARCHAR(50)   NOT NULL,
  data_nascimento  DATE          NULL,
  nota1            DECIMAL(4,2)  NOT NULL,
  nota2            DECIMAL(4,2)  NOT NULL
);

INSERT INTO alunos (nome, email, curso, data_nascimento, nota1, nota2) VALUES
('Beatriz Nunes', 'beatriz@escola.com', 'Informática', '2008-03-14', 9.0, 8.5),
('Lucas Ferreira', 'lucas@escola.com', 'Eletrônica', '2007-11-02', 5.0, 6.0),
('Rafaela Dias', 'rafaela@escola.com', 'Mecânica', NULL, 3.0, 4.0),
('Tiago Moreira', 'tiago@escola.com', 'Administração', '2008-07-21', 7.0, 6.5);
```

## Requisitos: FASE 1

Marque conforme for concluindo:

- [ ] **R1.** Criar o banco `escola_db` e a tabela `alunos` (repare no `UNIQUE` do e-mail) com os dados de exemplo.
- [ ] **R2.** Ajustar a conexão (`conexao.php`).
- [ ] **R3.** A **listagem** mostra: ID, Nome, E-mail, Curso, Nota 1, Nota 2, **Média** e **Situação**.
- [ ] **R4.** **Média** = (nota1 + nota2) ÷ 2, mostrada com **1 casa decimal e vírgula** (ex.: `7,5`). Ela **não é guardada no banco**.
- [ ] **R5.** **Situação**: média **≥ 6** → "Aprovado"; **de 4 até menos de 6** → "Recuperação" (selo amarelo); **menos de 4** → "Reprovado" (selo vermelho).
- [ ] **R6.** **Faixas no topo** (adapte o alerta de estoque): vermelha **"Reprovados"** e amarela **"Em recuperação"**, cada uma listando o **nome e a média** dos alunos (ex.: `Rafaela Dias (3,5)`). A média deve ser calculada **no SQL**.
- [ ] **R7.** **Formulário**: lista suspensa de curso, campo de data de nascimento, campos de nota que aceitam decimais (um dígito depois da vírgula).
- [ ] **R8.** **Validações no servidor**: nome obrigatório; e-mail válido; curso da lista; notas de 0 a 10; data de nascimento (opcional) válida e não futura.
- [ ] **R9.** **E-mail repetido**: mostrar a mensagem **"Já existe um aluno com este e-mail."** (e não um erro de SQL).
- [ ] **R10.** **Busca** por nome, e-mail **ou** curso. Lista em **ordem alfabética** pelo nome.
- [ ] **R11.** Textos ("Alunos", "Aluno cadastrado/atualizado/excluído com sucesso!") e **nenhum erro na tela**.

<details>
<summary><b>🔒 FASE 2: mudança de requisito (só abra depois de terminar e testar a Fase 1!)</b></summary>

A coordenação pediu duas mudanças:

- [ ] **F2.1 (15 pts).** Terceira nota: novo campo **`nota3`** (mesmas regras das outras notas). A média passa a ser **(nota1 + nota2 + nota3) ÷ 3**.
  ⚠️ A fórmula aparece em **mais de um lugar**: lista, faixas do topo e situação. O banco já tem alunos: use `ALTER TABLE` (alunos antigos ficam com `nota3` = 0).
- [ ] **F2.2 (15 pts).** Novo campo **`telefone`** (opcional). O usuário pode digitar `(11) 98888-7777`, mas o sistema **guarda só os dígitos** (`11988887777`). Precisa ter **10 ou 11 dígitos**; se não tiver, mostrar erro.

</details>

## Critérios de avaliação (Fase 1: 100 pontos)

| Requisito | Pontos |
|-----------|--------|
| R1 Banco e tabela (UNIQUE) | 10 |
| R2 Conexão | 5 |
| R3 Listagem | 15 |
| R4 Média calculada e formatada | 10 |
| R5 Situação por regra (com selos) | 10 |
| R6 Faixas de alunos em risco (SQL calcula) | 15 |
| R7 Formulário | 10 |
| R8 Validações | 10 |
| R9 E-mail repetido com mensagem amigável | 5 |
| R10 Busca e ordem | 5 |
| R11 Textos, sem erros na tela | 5 |

**Fase 2:** bônus de **30 pontos** (cada item vale o que está indicado).

## Dicas
- A **média** é uma coluna calculada: veja o exemplo no comentário `ADAPTAR (5)` do `index.php`.
- Para as faixas do topo, o SQL pode calcular: `SELECT nome, (nota1 + nota2) / 2 AS media FROM alunos WHERE ...`.
- Para o selo da situação, reaproveite as classes CSS `selo selo-baixo` (amarelo) e `selo selo-falta` (vermelho).
- E-mail repetido: veja o comentário no `catch` do `salvar.php` (código 1062).
- Os comentários do `salvar.php` têm modelos de validação de e-mail, nota, data e só dígitos.
