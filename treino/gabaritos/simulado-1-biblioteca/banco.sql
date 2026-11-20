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

CREATE DATABASE IF NOT EXISTS biblioteca_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE biblioteca_db;

DROP TABLE IF EXISTS livros;

CREATE TABLE livros (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  titulo          VARCHAR(150) NOT NULL,
  autor           VARCHAR(100) NOT NULL,
  editora         VARCHAR(80)  NULL,          -- FASE 2 (campo novo)
  genero          VARCHAR(50)  NULL,
  ano_publicacao  INT          NOT NULL,
  exemplares      INT          NOT NULL DEFAULT 0
);

INSERT INTO livros (titulo, autor, editora, genero, ano_publicacao, exemplares) VALUES
('Dom Casmurro', 'Machado de Assis', 'Ática', 'Romance', 1899, 4),
('O Cortiço', 'Aluísio Azevedo', 'Ática', 'Romance', 1890, 1),
('Vidas Secas', 'Graciliano Ramos', 'Record', 'Romance', 1938, 0),
('Memórias Póstumas de Brás Cubas', 'Machado de Assis', 'Globo', 'Romance', 1881, 2),
('O Pequeno Príncipe', 'Antoine de Saint-Exupéry', 'Agir', 'Infantil', 1943, 6);
