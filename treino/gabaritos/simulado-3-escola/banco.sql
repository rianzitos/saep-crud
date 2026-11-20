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

CREATE DATABASE IF NOT EXISTS escola_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE escola_db;

DROP TABLE IF EXISTS alunos;

CREATE TABLE alunos (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  nome             VARCHAR(100)  NOT NULL,
  email            VARCHAR(120)  NOT NULL UNIQUE,
  telefone         VARCHAR(11)   NULL,                          -- FASE 2 (campo novo)
  curso            VARCHAR(50)   NOT NULL,
  data_nascimento  DATE          NULL,
  nota1            DECIMAL(4,2)  NOT NULL,
  nota2            DECIMAL(4,2)  NOT NULL,
  nota3            DECIMAL(4,2)  NOT NULL DEFAULT 0             -- FASE 2 (campo novo)
);

INSERT INTO alunos (nome, email, telefone, curso, data_nascimento, nota1, nota2, nota3) VALUES
('Beatriz Nunes', 'beatriz@escola.com', '11988887777', 'Informática', '2008-03-14', 9.0, 8.5, 9.5),
('Lucas Ferreira', 'lucas@escola.com', NULL, 'Eletrônica', '2007-11-02', 5.0, 6.0, 5.5),
('Rafaela Dias', 'rafaela@escola.com', '1133334444', 'Mecânica', NULL, 3.0, 4.0, 2.0),
('Tiago Moreira', 'tiago@escola.com', NULL, 'Administração', '2008-07-21', 7.0, 6.5, 8.0);
