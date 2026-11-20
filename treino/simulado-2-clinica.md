# Simulado 2: Clínica: agenda de consultas

⏱ **Tempo sugerido:** 75 minutos · **Nível:** ⭐⭐ médio

> **Antes de começar:** trabalhe numa **cópia** da pasta do projeto, ligue o cronômetro e **não abra** a pasta `gabaritos/`.
> Guia completo de como treinar: [`LEIAME.md`](LEIAME.md).

## O que este simulado treina
- Chave primária que **não se chama `id`** (aqui é `codigo`): muda em vários arquivos.
- Campos de tipos diferentes: **select**, **data**, **hora**, **textarea**.
- Mostrar **data e hora formatadas** na lista.
- **Remover completamente** o alerta de estoque (esta clínica não tem estoque).
- Ordenar por **dois critérios** (data e hora).
- Na Fase 2: campo novo com `ALTER TABLE` e uma **regra de negócio** (consulta cancelada não é cobrada).

---

# 📄 PROPOSTA (como seria entregue na prova)

## Contexto
A clínica **Vida+** marca as consultas em um caderno e vive com horários duplicados. A recepção quer um sistema para **agendar, remarcar, cancelar e consultar** as consultas do dia.

## Banco de dados
Banco: **`clinica_db`** · Tabela: **`consultas`**

| Coluna | Tipo | Regras |
|--------|------|--------|
| **`codigo`** | INT, chave primária, auto incremento | ⚠️ atenção: **não se chama `id`** |
| `paciente` | VARCHAR(100) | obrigatório |
| `especialidade` | VARCHAR(50) | **lista fixa**: Clínico Geral, Cardiologia, Dermatologia, Pediatria |
| `data_consulta` | DATE | obrigatória, data válida |
| `horario` | TIME | obrigatório |
| `valor` | DECIMAL(10,2) | 0 ou mais |
| `status` | VARCHAR(20) | **lista fixa**: Agendada (padrão), Realizada, Cancelada |
| `observacoes` | TEXT | opcional, texto grande |

**Estrutura (execute no Workbench):**

```sql
CREATE DATABASE IF NOT EXISTS clinica_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinica_db;

CREATE TABLE consultas (
  codigo         INT AUTO_INCREMENT PRIMARY KEY,
  paciente       VARCHAR(100)  NOT NULL,
  especialidade  VARCHAR(50)   NOT NULL,
  data_consulta  DATE          NOT NULL,
  horario        TIME          NOT NULL,
  valor          DECIMAL(10,2) NOT NULL DEFAULT 0,
  status         VARCHAR(20)   NOT NULL DEFAULT 'Agendada',
  observacoes    TEXT          NULL
);

INSERT INTO consultas (paciente, especialidade, data_consulta, horario, valor, status, observacoes) VALUES
('Mariana Souza', 'Cardiologia', '2026-12-02', '09:30:00', 250.00, 'Agendada', 'Retorno com exames'),
('Carlos Lima', 'Clínico Geral', '2026-12-01', '14:00:00', 180.00, 'Agendada', NULL),
('Ana Paula Reis', 'Dermatologia', '2026-11-25', '08:15:00', 0.00, 'Realizada', 'Alta'),
('Pedro Alves', 'Pediatria', '2026-12-01', '10:45:00', 200.00, 'Cancelada', 'Paciente desmarcou');
```

## Requisitos: FASE 1

Marque conforme for concluindo:

- [ ] **R1.** Criar o banco `clinica_db` e a tabela `consultas` com os dados de exemplo.
- [ ] **R2.** Ajustar a conexão (`conexao.php`).
- [ ] **R3.** Todo o sistema (listar, **editar** e **excluir**) funcionando com a chave primária **`codigo`**.
- [ ] **R4.** A **listagem** mostra: Código, Paciente, Especialidade, **Data (dd/mm/aaaa)**, **Horário (hh:mm)**, **Valor (R$ 1.234,50)** e Status.
- [ ] **R5.** A lista aparece em ordem **cronológica**: primeiro por data, depois por horário (mais cedo primeiro).
- [ ] **R6.** A **busca** procura por paciente **ou** especialidade.
- [ ] **R7.** **Formulário** com: lista suspensa de especialidade, campo de data, campo de hora, valor, lista suspensa de status (já vem **"Agendada"** selecionado em consulta nova) e caixa de texto grande para observações.
- [ ] **R8.** **Validações no servidor**: paciente obrigatório; especialidade e status só aceitam valores da lista; data válida (ex.: `31/02` não existe); valor não pode ser negativo.
- [ ] **R9.** Esta clínica **não controla estoque**: **remova completamente** o alerta de estoque (faixas, selos e o código dele). Nada de estoque pode aparecer nem sobrar no `index.php`.
- [ ] **R10.** Textos: título da página **"Consultas"**; mensagens **"Consulta cadastrada com sucesso!"**, **"Consulta atualizada com sucesso!"** e **"Consulta excluída com sucesso!"**.
- [ ] **R11.** Tudo funciona **sem erros na tela**.

<details>
<summary><b>🔒 FASE 2: mudança de requisito (só abra depois de terminar e testar a Fase 1!)</b></summary>

A recepção viu o sistema funcionando e pediu duas mudanças:

- [ ] **F2.1 (15 pts).** Novo campo **`convenio`** (lista suspensa: Particular, Unimed, Bradesco Saúde, SUS; padrão **Particular**). Aparece na lista **depois de Especialidade** e no formulário, e é validado no servidor.
  O banco **já tem consultas**: use `ALTER TABLE`.
- [ ] **F2.2 (15 pts).** **Regra de negócio:** uma consulta com status **Cancelada** **nunca é cobrada**: mesmo que a recepção digite um valor, o sistema grava **0**.

</details>

## Critérios de avaliação (Fase 1: 100 pontos)

| Requisito | Pontos |
|-----------|--------|
| R1 Banco e tabela | 10 |
| R2 Conexão | 5 |
| R3 Funciona com a chave `codigo` | 15 |
| R4 Listagem formatada | 15 |
| R5 Ordem por data e horário | 5 |
| R6 Busca | 5 |
| R7 Formulário (tipos de campo certos) | 15 |
| R8 Validações no servidor | 15 |
| R9 Alerta de estoque removido por completo | 5 |
| R10 Textos e mensagens | 5 |
| R11 Funciona sem erros na tela | 5 |

**Fase 2:** bônus de **30 pontos** (cada item vale o que está indicado).

## Dicas
- Chave primária com outro nome: veja o item **"Chave primária com outro nome"** no `LEIAME.md` do projeto.
- Os comentários de `form.php` e `salvar.php` têm modelos prontos de **select**, **data**, **textarea** e **validações**.
- O comentário do `index.php` mostra como **formatar data e hora** e como ordenar por dois critérios.
- Para remover o alerta por completo: `Ctrl+F` por `ALERTA-ESTOQUE`.
