-- =====================================================
-- SAEP - Modelo de CRUD
-- COMO EXECUTAR (escolha UM jeito):
--   • MySQL Workbench: abra a conexão (ex.: "Local instance"), vá em
--     File > Open SQL Script... > escolha este banco.sql > clique no PRIMEIRO raio (⚡)
--     da barra do editor (ou Ctrl+Shift+Enter): ele executa o arquivo INTEIRO.
--     (Não use o raio com um "I" de cursor: esse executa só a linha onde o cursor está.)
--     Depois clique em "atualizar" (🔄) no painel SCHEMAS, à esquerda: o saep_db aparece lá.
--   • phpMyAdmin (http://localhost/phpmyadmin): aba "Importar" > escolher arquivo > Executar.
--   • Terminal: mysql -u root -p < banco.sql
--   ATENÇÃO: executar de novo APAGA e recria a tabela (os dados cadastrados somem).
--
-- >>> ADAPTAR — O QUE MUDAR NESTE ARQUIVO NA PROVA
--   1) Nome do banco (CREATE DATABASE e USE): tem que ser IGUAL ao $banco do conexao.php
--   2) Nome da tabela (DROP TABLE, CREATE TABLE e INSERT INTO)
--   3) Colunas: uma linha por campo, com o tipo certo (veja a tabela de tipos abaixo)
--   4) Dados de exemplo (INSERT): mesmas colunas, na mesma ordem
--
--   A prova já entregou um script .sql ou um banco pronto? Então use o DELES e
--   adapte os arquivos PHP para as colunas deles. Este arquivo vira só uma referência.
--
--   Tipos mais usados:
--     texto curto ......... VARCHAR(100)        texto longo ......... TEXT
--     número inteiro ...... INT                 dinheiro/decimal .... DECIMAL(10,2)
--     data ................ DATE                data e hora ......... DATETIME
--     sim/não ............. TINYINT(1)
--     NOT NULL = obrigatório | NULL = opcional | DEFAULT 0 = valor padrão
--
--   Exemplo — a prova pede LIVROS (titulo, autor, preco, estoque):
--     CREATE TABLE livros (
--       id       INT AUTO_INCREMENT PRIMARY KEY,
--       titulo   VARCHAR(100)  NOT NULL,
--       autor    VARCHAR(100)  NULL,
--       preco    DECIMAL(10,2) NOT NULL DEFAULT 0,
--       estoque  INT           NOT NULL DEFAULT 0
--     );
--     INSERT INTO livros (titulo, autor, preco, estoque) VALUES ('Dom Casmurro', 'Machado de Assis', 39.90, 12);
--
--   Depois de mudar a tabela aqui, mude também: index.php, form.php, salvar.php e excluir.php
--   (cada um deles tem comentários ">>> ADAPTAR" dizendo o que trocar).
-- =====================================================

CREATE DATABASE IF NOT EXISTS clinica_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE clinica_db;

DROP TABLE IF EXISTS consultas;

CREATE TABLE consultas (
  codigo         INT AUTO_INCREMENT PRIMARY KEY,
  paciente       VARCHAR(100)  NOT NULL,
  especialidade  VARCHAR(50)   NOT NULL,
  convenio       VARCHAR(50)   NOT NULL DEFAULT 'Particular',   -- FASE 2 (campo novo)
  data_consulta  DATE          NOT NULL,
  horario        TIME          NOT NULL,
  valor          DECIMAL(10,2) NOT NULL DEFAULT 0,
  status         VARCHAR(20)   NOT NULL DEFAULT 'Agendada',
  observacoes    TEXT          NULL
);

INSERT INTO consultas (paciente, especialidade, convenio, data_consulta, horario, valor, status, observacoes) VALUES
('Mariana Souza', 'Cardiologia', 'Unimed', '2026-12-02', '09:30:00', 250.00, 'Agendada', 'Retorno com exames'),
('Carlos Lima', 'Clínico Geral', 'Particular', '2026-12-01', '14:00:00', 180.00, 'Agendada', NULL),
('Ana Paula Reis', 'Dermatologia', 'SUS', '2026-11-25', '08:15:00', 0.00, 'Realizada', 'Alta'),
('Pedro Alves', 'Pediatria', 'Bradesco Saúde', '2026-12-01', '10:45:00', 200.00, 'Cancelada', 'Paciente desmarcou');
