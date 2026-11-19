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

CREATE DATABASE IF NOT EXISTS saep_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE saep_db;

DROP TABLE IF EXISTS produtos;

-- >>> ADAPTAR (2 e 3): troque "produtos" e as colunas abaixo.
--     A coluna "id" é a chave primária (AUTO_INCREMENT: o banco numera sozinho).
--     "criado_em" não é usada pelo PHP: pode apagar a linha (se apagar, tire a vírgula da linha de cima).
CREATE TABLE produtos (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nome        VARCHAR(100)   NOT NULL,
  descricao   VARCHAR(255)   NULL,
  preco       DECIMAL(10,2)  NOT NULL DEFAULT 0,
  quantidade  INT            NOT NULL DEFAULT 0,
  criado_em   TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- >>> ADAPTAR (4): mesmas colunas do CREATE TABLE (menos id e criado_em), na mesma ordem.
-- As 2 últimas linhas (Webcam com 3 e Headset com 0) existem só para você VER o ALERTA DE ESTOQUE
-- funcionando (limite padrão = 5). Pode apagá-las ou mudar as quantidades.
INSERT INTO produtos (nome, descricao, preco, quantidade) VALUES
('Teclado Mecânico', 'Teclado ABNT2 com switch azul', 249.90, 15),
('Mouse Gamer', 'Mouse óptico 6 botões', 89.90, 30),
('Monitor 24"', 'Monitor Full HD 75Hz', 799.00, 8),
('Webcam Full HD', 'Webcam 1080p com microfone', 159.90, 3),
('Headset USB', 'Headset com cancelamento de ruído', 129.90, 0);
